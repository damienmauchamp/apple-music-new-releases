<?php
/**
 * Created by PhpStorm.
 * User: dmauchamp
 * Date: 25/06/2018
 * Time: 10:32
 */

namespace AppleMusic;

use AppleMusic\DB as db;

class Album
{
    private $id;
    private $name;
    private $artistName;
    private $date;
    private $artwork;
    private $link;

    public function __construct()
    {

    }

    public static function withArray($array)
    {
        $instance = new self();
        $instance->fill($array);
        return $instance;
    }

    protected function fill($array)
    {
        $this->id = $array["id"];
        $this->name = $array["name"];
        $this->artistName = $array["artistName"];
        $this->date = $array["date"];
        $this->artwork = $array["artwork"];
        $this->link = "https://itunes.apple.com/fr/album/" . $array["id"];
    }

    public function addAlbum($idArtist)
    {
        $db = new db;
        $db->addAlbum($this, $idArtist);
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getArtistName()
    {
        return $this->artistName;
    }

    /**
     * @return mixed
     */
    public function getArtwork()
    {
        return $this->artwork;
    }
}