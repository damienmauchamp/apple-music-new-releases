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
//        $DB_nom = "applemusic-update";
        $env = explode(":", $this->getEnv());
        $DB_nom = $env[0] ? $env[0] : null;
        $DB_login = $env[1] ? $env[1] : null;
        $DB_psw = $env[2] ? $env[2] : null;

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

    public function getUserAlbums($userId = false)
    {
        global $idUser;

        if ($userId)
            $idUser = $userId;

        $sql = "
            SELECT
              al.id AS id, al.name AS name, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
              ar.id AS idArtist, ua.lastUpdate AS lastUpdate, al.explicit AS explicit
            FROM albums al
              LEFT JOIN artists_albums aa ON al.id = aa.idAlbum
              LEFT JOIN artists ar ON ar.id = aa.idArtist
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ua.idUser = :id_user AND ua.lastUpdate < al.date AND ua.active = 1
            ORDER BY ar.name ASC, al.date DESC";

        $this->connect();
//        $stmt = $this->dbh->query($sql);
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue("id_user", $idUser);
        $stmt->execute();
        $this->disconnect();

        $res = $stmt->fetchAll();
        return $res ? json_encode($res) : null;
    }

    public function getUserSongs($days = 7, $userId = false)
    {
        global $idUser;

        if ($userId)
            $idUser = $userId;

        $sql = "
            SELECT
              al.id AS id, al.collectionId AS collectionId, al.collectionName AS collectionName, al.trackName AS trackName, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
              ar.id AS idArtist, ua.lastUpdate AS lastUpdate, al.explicit AS explicit, al.isStreamable AS isStreamable
            FROM songs al
              LEFT JOIN artists_songs aa ON al.id = aa.idAlbum
              LEFT JOIN artists ar ON ar.id = aa.idArtist
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ua.idUser = :id_user AND al.date >= DATE_SUB(NOW(), INTERVAL :n_days DAY) AND ua.active = 1
            GROUP BY id
            ORDER BY al.isStreamable ASC, al.date ASC, ar.name ASC";

        $this->connect();
//        $stmt = $this->dbh->query($sql);
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue("id_user", $idUser);
        $stmt->bindValue("n_days", $days);
        $stmt->execute();
        $this->disconnect();

//        $res = $stmt->rowCount() > 0 ? $stmt->fetchAll() : null;
        $res = $stmt->fetchAll();
        return $res ? json_encode($res) : null;
    }

    public function getUsersArtists()
    {
        global $idUser;
        $sql = "
            SELECT ar.id, ar.name, ua.lastUpdate
            FROM artists ar
              LEFT JOIN users_artists ua ON ua.idArtist = ar.id
            WHERE ua.idUser = :id_user AND ua.active = 1
            ORDER BY name;";

        $this->connect();
//        $stmt = $this->dbh->query($sql);
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue("id_user", $idUser);
        $stmt->execute();
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
        global $idUser;
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
//        var_dump($sqlArtist, $sqlUserArtist);exit;
//        echo json_encode($sqlUserArtist);
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

    /**
     * @param Song $song
     * @param $idArtist
     * @return bool
     */
    public function addSong($song, $idArtist)
    {
        $id = $song->getId();
        $collectionId = $song->getCollectionId();
        $collectionName = addslashes($song->getCollectionName());
        $trackName = $song->getTrackName();
        $artistName = $song->getArtistName();
        $date = fixTZDate($song->getDate());
        $artwork = $song->getArtwork();
        $explicit = $song->isExplicit() ? 1 : 0;
        $isStreamable = $song->isStreamable() ? 1 : 0;

        $sqlAlbum = "
            INSERT INTO songs (id, collectionId, collectionName, trackName, artistName, date, artwork, explicit, isStreamable)
            VALUES ('$id', '$collectionId', '$collectionName', '$trackName', '$artistName', '$date', '$artwork', $explicit, $isStreamable)
            ON DUPLICATE KEY UPDATE id = '$id', collectionName = '$collectionName', trackName = '$trackName', artistName = '$artistName', date = '$date', artwork = '$artwork', explicit = '$explicit', isStreamable = $isStreamable";

        $sqlArtistAlbum = "
            INSERT INTO artists_songs (idArtist, idAlbum)
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
        global $idUser;
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
        global $idUser;
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
        global $idUser;
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT *
            FROM users_artists ua
            WHERE  ua.idUser = :idUser AND ua.idArtist = :idArtist;"
        );
        $stmt->execute(array("idUser" => $idUser, "idArtist" => $id));
        $res = $stmt->fetch();
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

    function logMail($description, $id_user)
    {
        $this->connect();
        $stmt = $this->dbh->prepare("
            INSERT INTO logs (type, date, id_user)
            VALUES (:description, :date, :user);"
        );
        $res = $stmt->execute(array("description" => $description, "date" => date("Y-m-d H:i:s"), "user" => $id_user));
        $this->disconnect();
        return $res;
    }

    public function logRefresh($type = "")
    {
        global $idUser;
        $refresh = $type ? "refresh $type" : "refresh";
        $this->connect();
        $stmt = $this->dbh->prepare("
            INSERT INTO logs (type, date, id_user)
            VALUES (:type, :date, :user);"
        );
        $res = $stmt->execute(array("type" => $refresh, "date" => date("Y-m-d H:i:s"), "user" => $idUser));
        $this->disconnect();
        return $res;
    }

    public function getLastRefresh($userId = null)
    {
        global $idUser;
        if ($userId) $idUser = $userId;
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT MAX(date)
            FROM logs
            WHERE id_user= :user;"
        );
        $stmt->execute(array("user" => $idUser));
        $res = $stmt->fetch();
        $this->disconnect();
        return $res[0];
    }

    public function getNotificationsStatus($userId = null)
    {
        global $idUser;
        if ($userId) $idUser = $userId;
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT notifications
            FROM users
            WHERE id= :user;"
        );
        $stmt->execute(array("user" => $idUser));
        $res = $stmt->fetch();
        $this->disconnect();
        return $res[0];
    }

    /**
     * @param bool $status
     * @param null $userId
     * @return mixed
     */
    public function setNotificationsStatus($status, $userId = null)
    {
        global $idUser;
        if ($userId) $idUser = $userId;
        $this->connect();
        $stmt = $this->dbh->prepare("
            UPDATE users
            SET notifications= :status
            WHERE id= :user;"
        );
        $res = $stmt->execute(array("user" => $idUser, "status" => $status));
        $this->disconnect();
        return $res;
    }

    public function getUsersIDs()
    {
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT id, prenom
            FROM users"
        );
        $stmt->execute();
        $res = $stmt->fetchAll();
        $this->disconnect();
        return $res;
    }

    public function connexion($username, $password)
    {
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT id, username, prenom
            FROM users
            WHERE username = :username AND password = :password"
        );
        $found = $stmt->execute(array("username" => $username, "password" => md5($password)));
        $res = $found ? $stmt->fetch() : null;
        $this->disconnect();
        return $res;
    }

    public function removeUsersArtist($idArtist)
    {
        global $idUser;
        $this->connect();
        $stmt = $this->dbh->prepare("
            DELETE FROM users_artists
            WHERE idArtist = :idArtist AND idUser = :idUser"
        );
        $res = $stmt->execute(array("idArtist" => $idArtist, "idUser" => $idUser));
        $this->disconnect();
        return $res;
    }

    public function getNotifiedUsers($force = false)
    {
        $this->connect();
        $stmt = $this->dbh->prepare("
            SELECT id, prenom, mail
            FROM users" . (!$force ? "
            WHERE notifications = TRUE" : "")
        );
        $stmt->execute();
        $res = $stmt->fetchAll();
        $this->disconnect();
        return $res;
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