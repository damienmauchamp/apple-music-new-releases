<?php

namespace AppleMusic;

use PDO;
use PDOException;

class DB
{
    /**
     * @var PDO null
     */
    protected $dbh;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        $this->dbh = null;
    }

    /**
     * Connexion à la base de données
     */
    private function connect()
    {
        $DB_serveur = "localhost";
        $DB_nom = "applemusic-update";
        $env = explode(":", $this->getEnv());
        $DB_login = $env[0] ? $env[0] : null;
        $DB_psw = $env[1] ? $env[1] : null;

        try {
            $this->dbh = new PDO('mysql:host=' . $DB_serveur . ';port=3307;dbname=' . $DB_nom, $DB_login, $DB_psw);
            $this->dbh->exec('SET CHARACTER SET utf8');
            $this->dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        } catch (PDOException $e) {
            echo "Erreur ! : " . $e->getMessage() . "<br/>";
            die("Connexion impossible à la base de données.");
        }
    }

    /**
     * Déconnexion de la base de données
     */
    private function disconnect()
    {
        $this->dbh = null;
    }

    private function getEnv()
    {
        return file_get_contents(dirname(__DIR__) . '/.env');
    }

    // GET

    public function getUserReleases()
    {
        $sql = "
            SELECT
              al.id AS id, al.name AS name, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
              ar.id AS idArtist, ua.lastUpdate AS lastUpdate, al.explicit AS explicit
            FROM albums al
              LEFT JOIN artists_albums aa ON al.id = aa.idAlbum
              LEFT JOIN artists ar ON ar.id = aa.idArtist
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ua.idUser = 1 AND ua.lastUpdate < al.date AND ua.active = 1
            ORDER BY ar.name ASC, al.date DESC";

        $this->connect();
        $stmt = $this->dbh->query($sql);
        $this->disconnect();

        $res = $stmt->fetchAll();
        return $res ? json_encode($res) : null;
    }

    public function getUsersArtists()
    {
        $sql = "
            SELECT ar.id, ar.name, ua.lastUpdate
            FROM artists ar
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ua.idUser = 1 AND ua.active = 1
            ORDER BY name;";

        $this->connect();
        $stmt = $this->dbh->query($sql);
        $this->disconnect();

        $res = $stmt->fetchAll();
        return $res ? json_encode($res) : null;
    }

    // POST

    /**
     * @param Artist $artist
     * @return bool
     */
    public function addArtist($artist)
    {
//        global $idUser;
        $idUser = 1;
        $id = $artist->getId();
        $name = addslashes($artist->getName());
        $sqlArtist = "
            INSERT INTO artists (id, name)
            VALUES ('$id', '$name')
            ON DUPLICATE KEY UPDATE id = '$id'";

        $sqlUserArtist = "
            INSERT INTO users_artists (idUser, idArtist, lastUpdate, active)
            VALUES ($idUser, '$id', NOW(), 1)
            ON DUPLICATE KEY UPDATE idUser = $idUser, idArtist = '$id'";

        $this->connect();
        $stmt = $this->dbh->prepare($sqlArtist);
        $resArtist = $stmt->execute();
        $stmt = $this->dbh->prepare($sqlUserArtist);
        $resUserArtist = $stmt->execute();
        $this->disconnect();
        echo json_encode($sqlUserArtist);
        return $resArtist && $resUserArtist;
    }

    /**
     * @param Album $album
     * @param $idArtist
     * @return bool
     */
    public function addAlbum($album, $idArtist)
    {
        $id = $album->getId();
        $name = addslashes($album->getName());
        $artistName = $album->getArtistName();
        $date = fixTZDate($album->getDate());
        $artwork = $album->getArtwork();
        $explicit = $album->isExplicit() ? 1 : 0;

        $sqlAlbum = "
            INSERT INTO albums (id, name, artistName, date, artwork, explicit)
            VALUES ('$id', '$name', '$artistName', '$date', '$artwork', $explicit)
            ON DUPLICATE KEY UPDATE id = '$id'";

        $sqlArtistAlbum = "
            INSERT INTO artists_albums (idArtist, idAlbum)
            VALUES ($idArtist, '$id')
            ON DUPLICATE KEY UPDATE idArtist = $idArtist, idAlbum = '$id'";

        $this->connect();
        $stmt = $this->dbh->prepare($sqlAlbum);
        $resAlbum = $stmt->execute();
        $stmt = $this->dbh->prepare($sqlArtistAlbum);
        $resArtistAlbum = $stmt->execute();
        $this->disconnect();
        return $resAlbum && $resArtistAlbum;
    }

    public function artistUpdated($idArtist, $minDate)
    {
//        global $idUser;
        $idUser = 1;
//        $date = "NOW()";
        $date = "$minDate 00:00:00";
        $sql = "
            UPDATE users_artists
            SET lastUpdate = '$date'
            WHERE idArtist = $idArtist AND idUser = $idUser";
        $this->connect();
        $stmt = $this->dbh->prepare($sql);
        $res = $stmt->execute();
        $this->disconnect();
        return $res;
    }

    public function getArtist($idArtist)
    {
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT ar.id AS id, ar.name AS name, ua.lastUpdate AS lastUpdate, ua.active AS active
            FROM artists ar
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ar.id = :idArtist"
        );
        $stmt->execute(array("idArtist" => $idArtist));
        $this->disconnect();

        $res = $stmt->fetch();
        return $res;
    }

    public function removeOldAlbums($days = 14)
    {
//        global $idUser;
        $idUser = 1;
        $this->connect();
        $stmt = $this->dbh->prepare("
            DELETE aa, al
            FROM albums al
              LEFT JOIN artists_albums aa ON al.id = aa.idAlbum
              LEFT JOIN artists ar ON ar.id = aa.idArtist
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ua.idUser = :idUser AND DATE_SUB(ua.lastUpdate, INTERVAL :days DAY) > al.date;"
        );
        $res = $stmt->execute(array("idUser" => $idUser, "days" => $days));
        $this->disconnect();
        return $res;
    }

    public function artistIsAdded($id)
    {
//        global $idUser;
        $idUser = 1;
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT *
            FROM users_artists ua
            WHERE  ua.idUser = :idUser AND ua.idArtist = :idArtist;"
        );
        $stmt->execute(array("idUser" => $idUser, "idArtist" => $id));
        $res = $stmt->fetchAll();
        $this->disconnect();
        return $res ? false : true;
    }

    public function selectPerso($sql)
    {
        $this->connect();
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute() or die("Erreur dans la requête.");
        $this->disconnect();
        return $stmt->fetchAll();
    }

    // Functions

    protected function setResults($array)
    {
        $res = array();
        foreach ($array as $key => $value) {
            if (!is_numeric($key))
                $res[$key] = $value;
        }
        return json_encode($res);
    }

//    public function example()
//    {
//        $this->connect();
//        $stmt = $this->dbh->query("SELECT * FROM albums");
//        $this->disconnect();
//
//        $res = $stmt->fetchAll();
//        return $res ? $this->setResults($res[0]) : null;
//    }
}