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

    public function getArtistDB()
    {
        $db = new DB();
        $array = $db->getArtist($this->id);
        if ($array) {
            $this->name =  isset($array["name"]) ? $array["name"] : null;
            $this->albums = isset($array["albums"]) ? $array["albums"] : null;
            $this->lastUpdate =  isset($array["lastUpdate"]) ? $array["lastUpdate"] : null;
        }
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

    public function update($date = false)
    {
        $db = new DB();

        $minDate = $date ? $date : $this->getAlbumsMinDate();
        $update = $db->artistUpdated($this->id, $minDate);
        return $update;
//        $removal = $db->removeOldAlbums($this);
//        var_dump($removal);
    }

    public function getAlbumsMinDate()
    {
        $min = date("Y-m-d");

        /** @var Album $album */
        foreach ($this->albums as $album) {
            $albumDate = $album->getDate();
            if (strtotime($min) > strtotime($albumDate) && strtotime($this->getLastUpdate()) > strtotime($albumDate))
                $min = $albumDate;
        }
        $tmp = str_replace('-', '/', $min);
        return date('Y-m-d', strtotime($tmp . "-1 days"));
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
        global $display;
        ?>
        <section class="artist l-content-width section section--bordered" data-am-artist-id="<?= $this->id ?>">
            <div class="section-header section__nav">
                <h2 class="section-title section__headline"><?= $this->name ?></h2>
                <a class="maj-link link section__nav__see-all-link ember-view"
                   data-am-artist-id="<?= $this->id ?>">MAJ</a>
                <a class="suppr-link link section__nav__see-all-link ember-view"
                   data-am-artist-id="<?= $this->id ?>">Suppr</a>
            </div>
            <div class="section-body l-row <?= $display == "row" ? "l-row--peek" : null ?>">
                <!--                <div class="scrolling">-->
                <? /** @var Album $album */
                foreach ($this->albums as $album) {
                    for ($i = 0; $i < 15; $i++)
                        echo $album->toString();
                } ?>
                <!--                </div>-->
            </div>
        </section>
        <?
//        echo "<pre>";
//        print_r($this);
//        echo "</pre>";
    }

    public function toJSON()
    {
        return json_encode(
            array(
                "id" => $this->id,
                "name" => $this->name,
                "lastUpdate" => $this->lastUpdate,
                "albumCount" => count($this->albums),
                "albums" => $this->albumsToJSONString()
            )
        );
    }

    public function albumsToJSONString($jsonReturn = false)
    {
        $array = array();

        /** @var Album $album */
        foreach ($this->albums as $album) {
            $array[] = $album->toString();
        }

        return $jsonReturn ? json_encode($array) : $array;
    }


}