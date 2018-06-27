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
        return $db->addAlbum($this, $idArtist);
    }

    public static function objectToArray($obj) {
        return array(
            "id" => $obj->id,
            "name" => $obj->name,
            "artistName" => $obj->artistName,
            "date" => $obj->date,
            "artwork" => $obj->artwork,
        );
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
     * @param int $width
     * @return mixed
     */
    public function getArtwork($width = 100)
    {
        return str_replace("100x100bb.jpg", "{$width}x{$width}bb.jpg", $this->artwork);
    }
}