<?php

namespace AppleMusic;

use AppleMusic\API as api;
use AppleMusic\DB as db;

class Artist
{
    private $id;
    private $name;
    public $albums;
    public $songs;
    private $lastUpdate;

    /**
     * Artist constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->albums = array();
        $this->songs = array();
    }

    public static function withArray($array)
    {
        $instance = new self($array["id"]);
        $instance->fill($array);
        return $instance;
    }

    public static function withNewRelease($array)
    {
        $db = new DB();
        $instance = self::withArray($array);
//        $instance->fetchArtistInfo();
        $artist = $db->getArtist($array["id"]);
        $instance->setName($artist["name"]);

        return $instance;
    }

    protected function fill($array)
    {
        $this->id = $array["id"];
        $this->name = $array["name"];
        $this->albums = isset($array["albums"]) ? $array["albums"] : array();
        $this->songs = isset($array["songs"]) ? $array["songs"] : array();
        $this->lastUpdate = $array["lastUpdate"];
    }

    public function getArtistDB()
    {
        $db = new DB();
        $array = $db->getArtist($this->id);
        if ($array) {
            $this->name = isset($array["name"]) ? $array["name"] : null;
            $this->albums = isset($array["albums"]) ? $array["albums"] : array();
            $this->songs = isset($array["songs"]) ? $array["songs"] : array();
            $this->lastUpdate = isset($array["lastUpdate"]) ? $array["lastUpdate"] : null;
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

        print_r(array(2, $date)); // debug
        $minDate = $date ? $date : $this->getAlbumsMinDate();
        print_r(array(3, $minDate)); // debug
        $update = $db->artistUpdated($this->id, $minDate);
        print_r(array(4, $update)); // debug
        /*$removal =*/
        $db->removeOldAlbums();
        return $update;
//        $removal = $db->removeOldAlbums($this);
//        var_dump($removal);
    }

    public function getAlbumsMinDate()
    {
        $min = date("Y-m-d");

        if ($this->albums) {
            /** @var Album $album */
            foreach ($this->albums as $album) {
                if ($album) {
                    $albumDate = $album->getDate();
                    if (strtotime(fixTZDate($min)) > strtotime(fixTZDate($albumDate)) && strtotime(fixTZDate($this->getLastUpdate())) > strtotime(fixTZDate($albumDate)))
                        $min = $albumDate;
                }
            }
        }

        if ($this->songs) {
            /** @var Song $song */
            foreach ($this->songs as $song) {
                if ($song) {
                    $songDate = $song->getDate();
                    if (strtotime(fixTZDate($min)) > strtotime(fixTZDate($songDate)) && strtotime(fixTZDate($this->getLastUpdate())) > strtotime(fixTZDate($songDate)))
                        $min = $songDate;
                }
            }
        }
        $tmp = str_replace('-', '/', $min);
        return date('Y-m-d', strtotime($tmp . "-1 days"));
    }

    public function removeUsersArtist()
    {
        $db = new db;
        return $db->removeUsersArtist($this->id);
    }

    public static function isAdded($id)
    {
        $db = new db;
        return $db->artistIsAdded($id);
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
     * @return mixed
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * @param mixed $albums
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;
    }

    /**
     * @param mixed $songs
     */
    public function setSongs($songs)
    {
        $this->songs = $songs;
    }

    /**
     * @param Album $album
     */
    public function setAnAlbum($album)
    {
        $this->albums[$album->getId()] = $album;
    }

    /**
     * @param Song $song
     */
    public function setASong($song)
    {
        $this->songs[$song->getId()] = $song;
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
        <section id="artist-<?= $this->id ?>" class="artist l-content-width section section--bordered"
                 data-am-artist-id="<?= $this->id ?>">
            <div class="section-header section__nav clearfix">
                <h2 class="section-title section__headline"><?= $this->name ?></h2>
                <a class="suppr-link link section__nav__see-all-link ember-view"
                   data-am-artist-id="<?= $this->id ?>" title="Supprimer">&times;</a>
                <a class="maj-link link section__nav__see-all-link ember-view"
                   data-am-artist-id="<?= $this->id ?>">MAJ</a>
            </div>
            <div class="section-body l-row <?= $display == "row" ? "l-row--peek" : null ?>">
                <?php /** @var Album $album */
                foreach ($this->albums as $album) {
//                    for ($i = 0; $i < 15; $i++)
                    echo $album->toString();
                } ?>
            </div>
        </section>
        <?php
        /*
        <!--section class="artist-songs l-content-width section" data-am-artist-id="<?= $this->id ?>">
            <div class="l-row">
                <div class="l-column small-12">
                    <h2 class="section__headline"><?= $this->name ?> - Morceaux</h2>
                    <table class="table table--see-all">
                        <thead class="table__head">
                        <tr>
                            <th class="table__head__heading--artwork"></th>
                            <th class="table__head__heading table__head__heading--song">TITRE</th>
                            <th class="table__head__heading table__head__heading--artist small-hide large-show-tablecell">
                                ARTISTE
                            </th>
                            <th class="table__head__heading table__head__heading--album small-hide medium-show-tablecell">
                                ALBUM
                            </th>
                            <th class="table__head__heading table__head__heading--duration --invisible">DURÉE</th>
                        </tr>
                        </thead>
                        <tbody>
                         ** @var Song $song *
        foreach ($this->songs as $song) {
            echo $song->toString();
        }
                        </tbody>
                    </table>
                </div>
            </div>
        </section--> */
    }

    public function toJSON()
    {
        return json_encode(
            array(
                "id" => $this->id,
                "name" => $this->name,
                "lastUpdate" => $this->lastUpdate,
                "albumCount" => ($this->albums ? count($this->albums) : 0),
                "albums" => $this->albumsToJSONString(),
                "songCount" => ($this->songs ? count($this->songs) : 0),
                "songs" => $this->songsToJSONString(),
                "_albums" => $this->albums,
                "_songs" => $this->songs
            )
        );
    }

    public function albumsToJSONString($jsonReturn = false)
    {
        $array = array();

        /** @var Album $a */
        foreach ($this->albums as $a) {
            /** @var Album $album */
            $album = isset($a) && is_array($a) && isset($a[0]) ? $a[0] : $a;
            if ($album)
                $array[] = $album->toString();
        }

        return $jsonReturn ? json_encode($array) : $array;
    }

    public function songsToJSONString($jsonReturn = false)
    {
        $array = array();

        if ($this->songs) {
            /** @var Song $s */
            foreach ($this->songs as $s) {
                /** @var Song $song */
                $song = isset($s) && is_array($s) && isset($s[0]) ? $s[0] : $s;
                $array[] = $song->toString();
            }
        }

        return $jsonReturn ? json_encode($array) : $array;
    }
}
?>