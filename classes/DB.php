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
        echo "ok";
    }

    /**
     * Connexion à la base de données
     */
    private function connect()
    {
        $DB_serveur = "localhost";
        $DB_nom = "applemusic-update";
        $DB_login = "damien";
        $DB_psw = "92iveyron";

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
}