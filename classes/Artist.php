<?php
/**
 * Created by PhpStorm.
 * User: dmauchamp
 * Date: 25/06/2018
 * Time: 10:20
 */

namespace AppleMusic;

use AppleMusic\API as api;
use AppleMusic\DB as db;

class Artist
{
    private $id;
    private $name;
    private $albums;

    /**
     * Artist constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function fetchArtistInfo()
    {
        $api = new api($this->id);
        $artist = $api->fetchArtist();
        $this->setName($artist->getName());
    }

    public function addArtist() {
        $db = new db;
        $db->addArtist($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * @param mixed $albums
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;
    }


}