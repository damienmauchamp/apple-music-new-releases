<?php

namespace AppleMusic;

use AppleMusic\Album as Album;

class API
{
    private $id;
    private $country = "fr";
    private $entity = "album";
    private $limit = 200;
    private $sort = "recent";

    /**
     * API constructor.
     * @param int $idArtist
     */
    public function __construct($idArtist = null)
    {
        $this->id = $idArtist;
    }

    public function searchArtist($search)
    {
        $this->entity = 'songs';
        $results = json_decode($this->curlSearch($search), true);
        return $this->fetch($results, "artistsSearch");
    }

    public function fetchArtist()
    {
        $results = json_decode($this->curlRequest(), true);
        return $this->fetch($results, "artist");
    }

    public function fetchAlbums()
    {
        $results = json_decode($this->curlRequest(), true);
        return $this->fetch($results, "albums");
    }

    public function fetchSongs()
    {
        $this->entity = "song";
        $this->limit = 30;
        $results = json_decode($this->curlRequest(), true);
        return $this->fetch($results, "songs");
    }

    /**
     * @param $results
     * @param $type
     * @return Artist|Album|array|null
     */
    protected function fetch($results, $type)
    {
        switch ($type) {
            case "songs":
                $songs = array();
                if (isset($results["results"])) {
                    foreach ($results["results"] as $collection) {
                        if ($collection["wrapperType"] === "track") {
                            $song = Song::withArray(
                                array(
                                    "id" => $collection["trackId"],
                                    "collectionId" => $collection["collectionId"],
                                    "collectionName" => $collection["collectionName"],
                                    "trackName" => $collection["trackName"],
                                    "name" => $collection["collectionName"],
                                    "artistName" => $collection["artistName"],
                                    "date" => $collection["releaseDate"],
                                    "artwork" => $collection["artworkUrl100"],
                                    "explicit" => $collection["collectionExplicitness"] == "explicit" ? true : false,
                                    "isStreamable" => $collection["isStreamable"]
                                )
                            );
                            $songs[] = $song;
                            //$songs = new Artist($collection["artistId"]);
                            //$songs->setName($collection["artistName"]);
                            break;
                        }
                    }
                }
                return $songs;
                break;
            case "artist":
                $artist = array();
                foreach ($results["results"] as $collection) {
                    if ($collection["wrapperType"] === "artist") {
                        $artist = new Artist($collection["artistId"]);
                        $artist->setName($collection["artistName"]);
                        break;
                    }
                }
                return $artist;
            case "albums":
                $albums = array();
                if (isset($results["results"])) {
                    foreach ($results["results"] as $collection) {
                        if ($collection["wrapperType"] === "collection") {
                            $album = Album::withArray(
                                array(
//                        "_id" => null,
                                    "id" => $collection["collectionId"],
                                    "name" => $collection["collectionName"],
                                    "artistName" => $collection["artistName"],
                                    "date" => $collection["releaseDate"],
                                    "artwork" => $collection["artworkUrl100"],
                                    "explicit" => $collection["collectionExplicitness"] == "explicit" ? true : false,
//                        "link" => $collection["collectionViewUrl"],
                                )
                            );
                            $albums[] = $album;
                        }
                    }
                }
                return $albums;
            case "artistsSearch":
                $ids = array();
                foreach ($results["results"] as $collection) {
                    $id = isset($collection["artistId"]) ? $collection["artistId"] : 0;

                    if (Artist::isAdded($id)) {
                        $n = isset($ids[$id]) ? ($ids[$id]["n"] + 1) : 1;
                        if ($n === 1) {
                            $ids[$id]["id"] = $id;
//                        $ids[$id]["text"] = $collection["artistName"];
                        }
                        $ids[$id]["n"] = $n;
                        $ids[$id]["names"][$collection["artistName"]] = isset($ids[$id]["names"][$collection["artistName"]]) ? $ids[$id]["names"][$collection["artistName"]] + 1 : 1;
                    }
                }
//                $ids[$id]["text"] = $collection["artistName"];

                foreach ($ids as $idA => $a) {
                    $max = 0;
                    $index = null;
                    foreach ($a["names"] as $name => $x) {
                        if ($x > $max) {
                            $max = $x;
                            $index = $name;
                        }
                    }
//                    $ids[$idA]["text"] = "$index (" . $ids[$idA]["n"] . ")";
                    $ids[$idA]["text"] = "$index";
                    $ids[$idA]["html"] = "<span class=\"artist-search-name\">$index</span> " . "<span class=\"artist-search-count\">" . $ids[$idA]["n"] . "</span>";
                }

                // ordre
                $this->array_sort_by_column($ids, "n", SORT_DESC);
                return $ids;
            default:
                return null;
        }
    }

    private function setAlbumsUrl()
    {
//        return $this->sort ? "https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit&sort=$this->sort&country=$this->country" : "https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit&country=$this->country";
        return "https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit" . ($this->sort ? "&sort=$this->sort" : "") . "&country=$this->country";
    }

    private function setArtistsSearchUrl($search)
    {
        //return "https://itunes.apple.com/search?term=$search&country=$this->country";
        return "https://itunes.apple.com/search?term=$search&entity=$this->entity&limit=$this->limit" . ($this->sort ? "&sort=$this->sort" : "") . "&country=$this->country";
    }

    private function curlRequest()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->setAlbumsUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        $header = array("Cache-Control: no-cache");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        return curl_exec($ch);
    }

    private function curlSearch($search)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->setArtistsSearchUrl($search));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        $header = array("Cache-Control: no-cache");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        return curl_exec($ch);
    }

    public function update($lastUpdate)
    {
        $albums = $this->fetchAlbums();
        $songs = $this->fetchSongs();
        $new = array(
            "albums" => array(),
            "songs" => array()
        );

        /** @var Album $album */
        foreach ($albums as $album) {
            $albumDate = date(DEFAULT_DATE_FORMAT . " 00:00:00", strtotime($album->getDate()));
            $lastUpdateDate = date(DEFAULT_DATE_FORMAT . " 00:00:00", strtotime($lastUpdate));
            if (strtotime($albumDate) >= strtotime($lastUpdateDate)) {
                $new["albums"][] = $album;
                $album->addAlbum($this->id);
            }
        }

        /** @var Song $song */
        foreach ($songs as $song) {
            $songDate = date(DEFAULT_DATE_FORMAT . " 00:00:00", strtotime($song->getDate()));
            $lastUpdateDate = date(DEFAULT_DATE_FORMAT . " 00:00:00", strtotime($lastUpdate));
            if (strtotime($songDate) >= strtotime($lastUpdateDate)) {
                $new["songs"][] = $song;
                $song->addSong($this->id);
            }
        }
        return $new;
    }

    private function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

}