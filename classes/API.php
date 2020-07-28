<?php

namespace AppleMusic;

use AppleMusic\Album as Album;
use Sunra\PhpSimple\HtmlDomParser;

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
        $this->entity = 'musicArtist';
        $results = json_decode($this->curlSearch($search), true);
        return $this->fetch($results, "artistsSearch");
    }

    public function fetchArtist()
    {
        $results = json_decode($this->curlRequest(), true);
        return $this->fetch($results, "artist");
    }

    public function fetchAlbums($scrapped = false, $artistName = '')
    {
        $json = $this->curlRequest($scrapped, $artistName);
        
        $db = new db;
        $db->logCurlRequest($this->id, $this->entity, $this->setAlbumsUrl($scrapped, $artistName), $json, $scrapped ? '1' : '0');

        $results = json_decode($json, true);
        //file_put_contents(LOG_FILE, "Albums found: " . json_encode($results) . "\n", FILE_APPEND);
        return $this->fetch($results, "albums", $scrapped);
    }

    public function fetchSongs($scrapped = false, $artistName = '')
    {
        $this->entity = 'song';
        //$this->limit = 200;
        $json = $this->curlRequest($scrapped, $artistName);

        $db = new db;
        $db->logCurlRequest($this->id, $this->entity, $this->setAlbumsUrl($scrapped, $artistName), $json, $scrapped ? '1' : '0');

        $results = json_decode($json, true);
        // print_r(['fetchSongs' => $results]);
        // print_r($results);
        // if ($this->id == "331066376") {
        //     file_put_contents(LOG_FILE, "Songs found: " . json_encode($results) . "\n", FILE_APPEND);
        // }
        return $this->fetch($results, "songs", $scrapped);
    }

    /**
     * @param $results
     * @param $type
     * @return Artist|Album|array|null
     */
    protected function fetch($results, $type, $scrapped = false)
    {
        switch ($type) {
            case "songs":
                $songs = array();

                if (!$scrapped) {
                    if (isset($results["results"])) {
                        foreach ($results["results"] as $collection) {
                            if ($collection["wrapperType"] === "track") {

                                /*if (strstr($collection["artistName"], 'Dinos')) {
                                    file_put_contents(LOG_FILE, "\nCREATING SONG: " . json_encode(array(
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
                                    )) . "\n", FILE_APPEND);
                                }*/

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

                                /*if (strstr($collection["artistName"], 'Dinos')) {
                                    file_put_contents(LOG_FILE, "\nADDING CREATED SONG: " . $song->getId() . "\n", FILE_APPEND);
                                }*/
                                $songs[] = $song;
                                //$songs = new Artist($collection["artistId"]);
                                //$songs->setName($collection["artistName"]);
                                //break;
                            }
                        }
                    }
                }
                else {
                    // print_r($results);
                    if (!$results['songs']) {
                        return $songs;
                    }

                    foreach ($results['songs'] as $collection) {

                        // check if the album is already in the db
                        $db = new db;
                        $verification_existence = $db->selectPerso("SELECT * FROM songs WHERE id = '{$collection['id']}'");

                        // print_r(['collection' => $collection]);
                        if ($verification_existence) {
                            // print_r(['verification_existence' => $verification_existence]);
                            continue;
                        }


                        // 
                        $explicit = isset($collection['attributes']['contentRatingsBySystem']) && 
                            isset($collection['attributes']['contentRatingsBySystem']['riaa']) && 
                            isset($collection['attributes']['contentRatingsBySystem']['riaa']['name']) && 
                            ($collection['attributes']['contentRatingsBySystem']['riaa']['name'] === "Explicit" || 
                                $collection['attributes']['contentRatingsBySystem']['riaa']['value'] > 0);

                        $artworkId = $collection['relationships']['artwork']['data']['id'];
                        $artworkAttributesMatches = array_filter($results['images'], function($relationship) use($artworkId) {
                            return $relationship['type'] === 'image' && $relationship['id'] === $artworkId;
                        });
                        $artworkAttributes = array_shift($artworkAttributesMatches);
                        $artworkUrl100 = str_replace('{w}x{h}bb.{f}', '100x100bb.jpg', $artworkAttributes['attributes']['url']);


                    // if (empty($collection['attributes']['releaseDate'])) {
                    //     print_r($collection);
                    // }
                        $releaseDate = $collection["attributes"]["releaseDate"];
                        if (preg_match('/^\d{4}$/', $collection["attributes"]["releaseDate"])) {
                            $releaseDate = "{$collection["attributes"]["releaseDate"]}-01-01";
                        } /*else if (preg_match('/^\d{4}\-\d{2}\-\d{2}/', $collection["attributes"]["releaseDate"])) {
                            $releaseDate = $collection["attributes"]["releaseDate"];
                        }*/

                        $today = new \DateTime();
                        $releaseDateTime = new \DateTime($releaseDate);
                        // $day = $interval->format('%r%a');
                        $isStreamable = $releaseDateTime < $today;
                        
                        $song = Song::withArray(
                            array(

                                // id
                                "id" => $collection["id"],
                                // collectionId
                                "collectionId" => preg_replace('/^(.*)\/(\d+)\?i=(\d+)$/', '$2', $collection["attributes"]["url"]),
                                // collectionName
                                "collectionName" => $collection["attributes"]["collectionName"],
                                // trackName
                                "trackName" => $collection["attributes"]["name"],
                                // artistName
                                "artistName" => $collection["attributes"]["artistName"],
                                // date
                                "date" => $releaseDate,
                                // artwork
                                "artwork" => $artworkUrl100,
                                // explicit
                                "explicit" => $explicit,
                                // isStreamable
                                "isStreamable" => $isStreamable,

//                        "_id" => null,
//                        "link" => $collection["collectionViewUrl"],
                            )
                        );
                        $songs[] = $song;
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

                if (!$scrapped) {
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
                }
                else {
                    if (!$results['albums']) {
                        return $albums;
                    }

                    foreach ($results['albums'] as $collection) {

                        // check if the album is already in the db
                        $db = new db;
                        $verification_existence = $db->selectPerso("SELECT * FROM albums WHERE id = '{$collection['id']}'");

                        if ($verification_existence) {
                            //print_r($verification_existence);
                            continue;
                        }

                        // 
                        $explicit = isset($collection['attributes']['contentRatingsBySystem']) && 
                            isset($collection['attributes']['contentRatingsBySystem']['riaa']) && 
                            isset($collection['attributes']['contentRatingsBySystem']['riaa']['name']) && 
                            ($collection['attributes']['contentRatingsBySystem']['riaa']['name'] === "Explicit" || 
                                $collection['attributes']['contentRatingsBySystem']['riaa']['value'] > 0);

                        $artworkId = $collection['relationships']['artwork']['data']['id'];
                        $artworkAttributesMatches = array_filter($results['images'], function($relationship) use($artworkId) {
                            return $relationship['type'] === 'image' && $relationship['id'] === $artworkId;
                        });
                        $artworkAttributes = array_shift($artworkAttributesMatches);
                        $artworkUrl100 = str_replace('{w}x{h}bb.{f}', '100x100bb.jpg', $artworkAttributes['attributes']['url']);

                        $releaseDate = $collection["attributes"]["releaseDate"];
                        if (preg_match('/^\d{4}$/', $collection["attributes"]["releaseDate"])) {
                            $releaseDate = "{$collection["attributes"]["releaseDate"]}-01-01";
                        } /*else if (preg_match('/^\d{4}\-\d{2}\-\d{2}/', $collection["attributes"]["releaseDate"])) {
                            $releaseDate = $collection["attributes"]["releaseDate"];
                        }*/
                        
                        $album = Album::withArray(
                            array(
//                        "_id" => null,
                                "id" => $collection["id"],
                                "name" => $collection["attributes"]["name"],
                                "artistName" => $collection["attributes"]["artistName"],
                                "date" => $releaseDate,
                                "artwork" => $artworkUrl100,
                                "explicit" => $explicit,
//                        "link" => $collection["collectionViewUrl"],
                            )
                        );
                        $albums[] = $album;
                    }

                }
                return $albums;
            case "artistsSearch":
                /*$searchResults = [];
                foreach ($results["results"] as $artistItem) {
                    $genre = !empty($artistItem['primaryGenreName']) ? "({$artistItem['primaryGenreName']})" : "";
                    // ({$artistItem['primaryGenreName']})
                    $searchResults[] = "<span class=\"artist-search-name\">{$artistItem['artistName']}</span> <span class=\"artist-search-count\"></span>";
                }
                return $searchResults;*/
                $ids = array();
                foreach ($results["results"] as $collection) {
                    $id = isset($collection["artistId"]) ? $collection["artistId"] : 0;

                    if (Artist::isAdded($id)) {
                        $n = isset($ids[$id]) ? ($ids[$id]["n"] + 1) : 1;
                        if ($n === 1) {
                            $ids[$id]["id"] = $id;
//                        $ids[$id]["text"] = $collection["artistName"];
                        }
                        $ids[$id]["primaryGenreName"] = !empty($collection["primaryGenreName"]) ? $collection["primaryGenreName"] : '';
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

                    $genre = $ids[$idA]["primaryGenreName"] ? "({$ids[$idA]["primaryGenreName"]})" : '';

//                    $ids[$idA]["text"] = "$index (" . $ids[$idA]["n"] . ")";
                    $ids[$idA]["text"] = "$index";
                    //$ids[$idA]["html"] = "<span class=\"artist-search-name\">$index</span> " . "<span class=\"artist-search-count\">" . $ids[$idA]["n"] . "</span>";
                    $ids[$idA]["html"] = "<span class=\"artist-search-name\">{$index}</span> " . "<span class=\"artist-search-count\">{$genre}</span>";
                }

                // ordre
                $this->array_sort_by_column($ids, "n", SORT_DESC);
                return $ids;

                //primaryGenreName
            default:
                return null;
        }
    }

    private function setAlbumsUrl($scrapped = false, $artistName = '')
    {
//        return $this->sort ? "https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit&sort=$this->sort&country=$this->country" : "https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit&country=$this->country";
        /*if ($this->id == "331066376") {
            file_put_contents(LOG_FILE, "\nSONG REQUEST: https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit" . ($this->sort ? "&sort=$this->sort" : "") . "&country=$this->country\n", FILE_APPEND);
        }*/
        if ($scrapped) {
            // return "https://music.apple.com/{$this->country}/artist/aaa/{$this->id}";
            $artistUrlName = $artistName ? strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $artistName), '-')) : 'xxx';
            // echo "https://itunes.apple.com/{$this->country}/artist/{$artistUrlName}/{$this->id}\n";
            return "https://itunes.apple.com/{$this->country}/artist/{$artistUrlName}/{$this->id}";
        }
        return "https://itunes.apple.com/lookup?id=$this->id&entity=$this->entity&limit=$this->limit" . ($this->sort ? "&sort=$this->sort" : "") . "&country=$this->country";
    }

    private function setArtistsSearchUrl($search)
    {
        //return "https://itunes.apple.com/search?term=$search&country=$this->country";
        return "https://itunes.apple.com/search?term=$search&entity=$this->entity&limit=$this->limit" . ($this->sort ? "&sort=$this->sort" : "") . "&country=$this->country";
    }

    private function curlRequest($scrapped = false, $artistName = '')
    {
        if ($scrapped) {
            return $this->curlScrappedRequest($artistName);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->setAlbumsUrl(false, $artistName));
        // echo $this->setAlbumsUrl($scrapped);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        $header = array("Cache-Control: no-cache");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        return curl_exec($ch);
    }

    private function curlScrappedRequest($artistName = '') {
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

           // CURLOPT_CUSTOMREQUEST => 'GET',        //set request type post or get
            CURLOPT_POST => false,        //set to GET
            CURLOPT_USERAGENT => $user_agent, //set user agent
            CURLOPT_COOKIEFILE => "cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR => "cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING => "",       // handle all encodings
            CURLOPT_AUTOREFERER => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT => 120,      // timeout on response
            CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        );

        $url = $this->setAlbumsUrl(true, $artistName);

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = trim($content);

        $dom = HtmlDomParser::str_get_html($header["content"]);
        if (!$dom) {
            return json_encode([
                'albums' => [],
                'songs' => [],
                'images' => [],
            ]);
        }

        $elems = $dom->find('script#shoebox-ember-data-store');
        $data = [];
        foreach ($elems as $e) {
            $data = json_decode($e->innertext, true);
            break;
        }

        //exit(json_encode($data));

        $albums = $songs = $images = [];

        // echo '<pre>' . print_r($data, true) . '</pre>';
        // echo '<pre>' . print_r($data, true) . '</pre>';exit();

        if (isset($data['included'])) {
            foreach ($data['included'] as $include) {
                //echo "{$include['type']} - " . ($include['type'] === "lockup/album" ? "OUI" : "NON") . "\n";
                if ($include['type'] === 'lockup/album') {

                    $releaseDate = new \DateTime($include['attributes']['releaseDate']);
                    $today = new \DateTime();
                    $interval = $releaseDate->diff($today);
                    $day = $interval->format('%r%a');
                    if ($day < 7) {
                        $albums[] = $include;
                    }

                }
                if ($include['type'] === 'lockup/song') {

                    // if (empty($include['attributes']['releaseDate'])) {
                    //     print_r($include);
                    // }

                    // "collectionId" => preg_replace('/^(.*)\/(\d+)\?i=(\d+)$/', '$2', $collection["attributes"]["url"]),

                    if (!isset($include['attributes']['releaseDate']) && !preg_match('/\- Single$/', $include['attributes']['collectionName'])) {
                        $collectionId = preg_replace('/^(.*)\/(\d+)\?i=(\d+)$/', '$2', $include["attributes"]["url"]);

                        $collections = array_filter($data['included'], static function($entity) use ($collectionId) {
                            return $entity['type'] === 'lockup/album' && $entity['id'] === $collectionId;
                        });
                        if (!$collections) {
                            continue;
                        }

                        $collection = array_shift($collections);
                        if (!isset($collection['attributes']['releaseDate'])) {
                            continue;
                        }

                        $include['attributes']['releaseDate'] = $collection['attributes']['releaseDate'];
                    }

                    $releaseDate = new \DateTime($include['attributes']['releaseDate']);
                    $today = new \DateTime();
                    $interval = $releaseDate->diff($today);
                    $day = (int) $interval->format('%r%a');
                    // print_r([
                    //     $releaseDate,
                    //     $today,
                    //     $interval,
                    // ]);

                    // var_dump([
                    //     $releaseDate,
                    //     $today,
                    //     $interval,
                    //     $day,
                    // ]);
                    if ($day < 7) {
                        $songs[] = $include;
                    }

                }
                else if ($include['type'] === 'image') {
                    $images[] = $include;
                }
            }
        }

        return json_encode([
            'albums' => $albums,
            'songs' => $songs,
            'images' => $images,
        ]);
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

    public function update($lastUpdate, $scrapped = false, $artistName = '')
    {
        //file_put_contents(LOG_FILE, "Fetching albums\n", FILE_APPEND);
        $albums = $this->fetchAlbums($scrapped, $artistName);
        //file_put_contents(LOG_FILE, "Fetching songs\n", FILE_APPEND);
        $songs = $this->fetchSongs($scrapped, $artistName);

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
                //file_put_contents(LOG_FILE, "\nADDING SONG " . json_encode(['collectionId' => $song->getCollectionId(), 'collectionName' => $song->getCollectionName(), 'trackName' => $song->getTrackName(), 'artistName' => $song->getArtistName()]) . "\n", FILE_APPEND);
                $new["songs"][] = $song;
                $song->addSong($this->id);
            } else {
                /*if (strstr($song->getArtistName(), 'Dinos')) {
                    $array_log = ["songDate" => strtotime($songDate), "lastUpdateDate" => strtotime($lastUpdateDate)];
                    file_put_contents(LOG_FILE, "\nNOT ADDING SONG " . json_encode($song) . ", reason: " . json_encode($array_log) . " \n", FILE_APPEND);
                }*/
            }
        }

        // print_r($new);
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