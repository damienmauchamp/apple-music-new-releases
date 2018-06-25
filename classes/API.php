<?php
/**
 * Created by PhpStorm.
 * User: dmauchamp
 * Date: 25/06/2018
 * Time: 10:25
 */

namespace AppleMusic;

use AppleMusic\Album as Album;
use AppleMusic\DB as db;

class API
{
    private $id;
    private $country = "fr";
    private $entity = "album";
    private $limit = 5;
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

    protected function fetch($results, $type)
    {
        switch ($type) {
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
//                        "link" => $collection["collectionViewUrl"],
                            )
                        );
                        $albums[] = $album;
                    }
                }
                return $albums;
            case "artistsSearch":
                $ids = array();
                foreach ($results["results"] as $collection) {
                    $id = $collection["artistId"];
                    $n = isset($ids[$id]) ? ($ids[$id]["n"] + 1) : 1;
                    if ($n === 1) {
                        $ids[$id]["id"] = $id;
                        $ids[$id]["text"] = $collection["artistName"];
                    }
                    $ids[$id]["n"] = $n;
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
        return "https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit&sort=$this->sort&country=$this->country";
    }

    private function setArtistsSearchUrl($search)
    {
        return "https://itunes.apple.com/search?term=$search&country=$this->country";
    }

    private function curlRequest()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->setAlbumsUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);
    }

    private function curlSearch($search)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->setArtistsSearchUrl($search));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);
    }

    public function update($lastUpdate)
    {
        $albums = $this->fetchAlbums();
        $new = array();

        /** @var Album $album */
        foreach ($albums as $album) {
            if (strtotime($album->getDate()) > strtotime($lastUpdate)) {
                $new[] = $album;
                $album->addAlbum($this->id);
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