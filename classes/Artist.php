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
    public $albums;
    private $lastUpdate;

    /**
     * Artist constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->albums = array();
    }

    public static function withArray($array)
    {
        $instance = new self($array["id"]);
        $instance->fill($array);
        return $instance;
    }

    public static function withNewRelease($array)
    {
        $instance = self::withArray($array);
        $instance->fetchArtistInfo();
        return $instance;
    }

    protected function fill($array)
    {
        $this->id = $array["id"];
        $this->name = $array["name"];
        $this->albums = $array["albums"];
        $this->lastUpdate = $array["lastUpdate"];
    }

    public function fetchArtistInfo()
    {
        $api = new api($this->id);
        /** @var Artist $artist */
        $artist = $api->fetchArtist();
        $this->setName($artist->getName());
        $this->setLastUpdate($artist->getLastUpdate());
    }

    public function addArtist()
    {
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

    /**
     * @param Album $album
     */
    public function setAnAlbum($album)
    {
//        var_dump(count($this->albums));
        $this->albums[$album->getId()] = $album;
//        var_dump($this->albums);
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    public function toString()
    {
        ?>
        <section class="artist" amu-artist-id="<?= $this->id ?>">
            <div class="section-header">
                <h2 class="section-title"><?= $this->name ?></h2>
            </div>
            <div class="section-body">
                <? /** @var Album $album */
                foreach ($this->albums as $album) : ?>
                    <a class="album">
                        <picture class="artwork">
                            <img src="<?= $album->getArtwork() ?>"
                                 style="background-color: #515e64;" class="we-artwork__image ember796" alt="">
                        </picture>
                        <h3 class="album-title"></h3>
                        <h4 class="album-year"></h4>
                    </a>
                <? endforeach; ?>
            </div>
        </section>
        <?

        echo "<pre>";
        print_r($this);
        echo "</pre>";
    }


}