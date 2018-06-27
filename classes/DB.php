<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 24/06/2018
 * Time: 20:30
 */

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

        if ($_SERVER['HTTP_HOST'] == "local.workspace.vm") {
            $DB_login = "dmauchamp";
            $DB_psw = "azerty123";
        } else {
            $DB_login = "damien";
            $DB_psw = "92iveyron";
        }

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

    //////////
    /// API

    // GET

    public function getUserReleases()
    {
        $sql = "
            SELECT
              al.id AS id, al.name AS name, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
              ar.id AS idArtist, ua.lastUpdate AS lastUpdate
            FROM albums al
              LEFT JOIN artists_albums aa ON al.id = aa.idAlbum
              LEFT JOIN artists ar ON ar.id = aa.idArtist
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ua.idUser = 1 AND ua.lastUpdate < al.date AND ua.active = 1
            GROUP BY aa.idAlbum";

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
        $id = $artist->getId();
        $name = $artist->getName();
        $idUser = 1;
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
        $date = $album->getDate();
        $artwork = $album->getArtwork();

        $sqlAlbum = "
            INSERT INTO albums (id, name, artistName, date, artwork)
            VALUES ('$id', '$name', '$artistName', '$date', '$artwork')
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

    public function updated($idArtist)
    {
        $idUser = 1;
        $sql = "
            UPDATE users_artists
            SET lastUpdate = NOW()
            WHERE idArtist = $idArtist AND idUser = $idUser";
        $this->connect();
        $stmt = $this->dbh->prepare($sql);
//        $res = $stmt->execute();
        $this->disconnect();
    }

    public function example()
    {
        $this->connect();
        $stmt = $this->dbh->query("SELECT * FROM albums");
        $this->disconnect();

        $res = $stmt->fetchAll();
        return $res ? $this->setResults($res[0]) : null;
    }

    public function selectPerso($sql)
    {
        $this->connect();
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute() or die("Erreur dans la requête.");
        $this->disconnect();
        return $stmt->fetchAll();
    }

    // Fonctions

    protected function setResults($array)
    {
        $res = array();
        foreach ($array as $key => $value) {
            if (!is_numeric($key))
                $res[$key] = $value;
        }
        return json_encode($res);
    }
}