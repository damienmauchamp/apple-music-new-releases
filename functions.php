<?php

use AppleMusic\DB as db;
use AppleMusic\API as api;
use AppleMusic\Artist as Artist;
use AppleMusic\Album as Album;
use AppleMusic\Song as Song;

function displayAlbums($albums)
{
    if ($albums) {
        /** @var Album $album */
        foreach ($albums as $album) {
            echo Album::withArray(Album::objectToArray($album))->toString("albums");
        }
    }
}

function displaySongs($songs)
{
    if ($songs) {
        /** @var Song $songs */
        foreach ($songs as $song) {
            echo Song::withArray(Song::objectToArray($song))->toString();
        }
    }
}

function getThisWeekReleases($allow_duplicates = false) {
    $db = new db;
    $releases = $db->getUserWeekReleases();

    if (!$releases) {
        return '';
    }

    $html = '
        <section class="main-header l-content-width section section--bordered">
            <div class="section-header section__headline">
                <h2 class="section-title section__headline" style="font-size: 120%;">Sorties de la semaine</h2>
            </div>

            <div class="section-body l-row">';

    $array_releases = [];
    foreach (json_decode($releases) as $r) {
        // avoiding duplicates + removing non explicits
        $str = trim(preg_replace('/([^A-Za-z0-9]|(\s))*/', '', "{$r->name} {$r->artistName}"));
        if (!$allow_duplicates && !empty($array_releases[$str])) {
            continue;
        }
        $array_releases[$str] = true;

        $artistId = $r->idArtist;
        $album = Album::withArray(Album::objectToArray($r));
        if (!isset($artists[$artistId])) {
            $artists[$artistId] = array(
                "id" => $artistId,
                "name" => $r->artistName,
                "albums" => array(),
                "lastUpdate" => $r->lastUpdate
            );
        }
        $display = 'grid';
        $html .= $album->toString('grid');
    }

    $html .= '
            </div>
        </section>';

    return $html;
}

function getUpcomingReleases() {
    $db = new db;
    $releases = $db->getUserUpcomingReleases();

    if (!$releases) {
        return '';
    }

    $html = '
        <section class="l-content-width section section--bordered" data-am-artist-id="968750077">
            <div class="section-header section__nav clearfix">
                <h2 class="section-title section__headline">À venir</h2>
            </div>
            <div class="section-body l-row l-row--peek">';

    $array_releases = [];
    foreach (json_decode($releases) as $r) {
        // avoiding duplicates + removing non explicits
        $str = trim(preg_replace('/([^A-Za-z0-9]|(\s))*/', '', "{$r->name} {$r->artistName}"));
        if (!empty($array_releases[$str])) {
            continue;
        }
        $array_releases[$str] = true;

        $artistId = $r->idArtist;
        $album = Album::withArray(Album::objectToArray($r));
        if (!isset($artists[$artistId])) {
            $artists[$artistId] = array(
                "id" => $artistId,
                "name" => $r->artistName,
                "albums" => array(),
                "lastUpdate" => $r->lastUpdate
            );
        }
        $display = 'row';
        $html .= $album->toString('row');
    }

    //$html .= '<pre>'.print_r($releases, 1).'</pre>';

    $html .= '
            </div>
        </section>';

    return $html;

}

// Dernières sorties depuis actualisation, dans la BD
function getAllAlbums($display = "artists")
{
    $db = new db;
    $releases = $db->getUserAlbums();
    $artists = array();

    if (!$releases)
        return json_decode($releases);

    if ($display === "albums") {
        echo '
    	<section class="artist l-content-width section">
            <div class="section-header section__headline">
                <h2 class="section-title">Tous les albums</h2>
            </div>

            <div class="section-body l-row">';
        foreach (json_decode($releases) as $r) {
            $artistId = $r->idArtist;
            $album = Album::withArray(Album::objectToArray($r));
            if (!isset($artists[$artistId])) {
                $artists[$artistId] = array(
                    "id" => $artistId,
                    "name" => $r->artistName,
                    "albums" => array(),
                    "lastUpdate" => $r->lastUpdate
                );
            }
            $album->toString();
        }
        echo '
            </div>
        </section>';
    } else { //"artists"
        foreach (json_decode($releases) as $r) {
            // Artiste
            $artistId = $r->idArtist;
            $album = Album::withArray(Album::objectToArray($r));
            if (!isset($artists[$artistId])) {
                $artists[$artistId] = array(
                    "id" => $artistId,
                    "name" => $r->artistName,
                    "albums" => array(),
                    "lastUpdate" => $r->lastUpdate
                );
            }
            $artists[$artistId]["albums"][] = $album;
        }

        foreach ($artists as $artist) {
            Artist::withNewRelease($artist)->toString();
        }
    }
    return json_decode($releases);
}

function getAllSongs($filtrer_albums = false)
{
    global $daysInterval;
    $db = new db;
    $releases = $db->getUserSongs($daysInterval);
    $artists = array();

    if ($filtrer_albums) {
        $albums = array_map(static function($album) {
            return $album['id'];
        }, json_decode($db->getUserWeekReleases(), true));
    }

    $releases_array = [];
    if ($releases) {
        $releases_array = json_decode($releases);
        foreach ($releases_array as $i => $r) {
            $artistId = $r->idArtist;
            $song = Song::withArray(Song::objectToArray($r));

            if ($filtrer_albums && in_array($r->collectionId, $albums)) {
                unset($releases_array[$i]);
                continue;
            }

            if (!isset($artists[$artistId])) {
                $artists[$artistId] = array(
                    "id" => $artistId,
                    "name" => $r->artistName,
                    "albums" => array(),
                    "songs" => array(),
                    "lastUpdate" => $r->lastUpdate
                );
            }
            $artists[$artistId]["songs"][] = $song;
        }

//        foreach ($artists as $artist) {
//            Artist::withNewRelease($artist)->toString();
//        }
    }

    return $releases_array;
}


// Récupère tous les artistes
function getAllNewReleases()
{
    $db = new db;
    $releases = array();
    /*$removal =*/
    $db->removeOldAlbums();
    $db->removeOldSongs();
    $artists = $db->getUsersArtists();
    if (!$artists) {
        return $releases;
    }
    foreach (json_decode($artists) as $artist) {
        $releases[] = getArtistRelease($artist);
//        break;
    }
    return $releases;
}

function getAllNewScrappedReleases()
{
    $db = new db;
    $releases = array();
    /*$removal =*/
    $artists = $db->getUsersArtists();
    if (!$artists) {
        return $releases;
    }
    foreach (json_decode($artists) as $artist) {
        $releases[] = getArtistScrappedRelease($artist);
//        break;
    }
    return $releases;
}

function removeOldAlbums($days = 180)
{
    $db = new db;
    return $db->removeOldAlbums($days);
}

/**
 * @param $objArtist
 * @param bool $display
 * @return mixed
 */
function getArtistRelease($objArtist, $display = false)
{
//    $db = new db;
    global $nodisplay;
    // Artiste
    $artist = new Artist($objArtist->id);
    $artist->setName($objArtist->name);
    $artist->setLastUpdate($objArtist->lastUpdate);

    //https://itunes.apple.com/search?term=Dinos&entity=songs&limit=200&sort=recent&country=fr
    //file_put_contents(LOG_FILE, "\n\n\nFetching artist {$objArtist->name}, id {$objArtist->id}\n", FILE_APPEND);

    // Recupération des albums sur l'API
    $api = new api($artist->getId());
    $newEntities = $api->update($artist->getLastUpdate());

    $artist->setAlbums($newEntities["albums"]);
    $artist->setSongs($newEntities["songs"]);

    $albums = $artist->getAlbums();
    $songs = $artist->getSongs();

    /*if ($objArtist->id == "331066376") {
        file_put_contents(LOG_FILE, "\n\n\n---> {$objArtist->name}, id:{$objArtist->id}\n", FILE_APPEND);
        file_put_contents(LOG_FILE, "\nALBUMS " . json_encode($albums) . "\n", FILE_APPEND);
        file_put_contents(LOG_FILE, "\nSONGS " . json_encode($songs) . "\n", FILE_APPEND);
    }*/

    // Mise en BD des nouveaux albums & nouvelles chansons

    /** @var Album $album */
    foreach ($albums as $album) {
        // Ajout de l'album à la BD
        $album->addAlbum($artist->getId());
//        $artist->update();
        echo $nodisplay ? null : $album->toString($display, $artist->getId());
    }

    if ($songs) {
        /** @var Song $song */
        foreach ($songs as $song) {
            // Ajout de l'album à la BD
            $song->addSong($artist->getId());
//        $artist->update();
            //echo $nodisplay ? null : $song->toString($display);
        }
    }
    return array("albums" => $albums, "songs" => $songs);
}

function getArtistScrappedRelease($objArtist, $display = false) {
//    $db = new db;
    global $nodisplay;
    // Artiste
    $artist = new Artist($objArtist->id);
    $artist->setName($objArtist->name);
    $artist->setLastUpdate($objArtist->lastUpdate);

    //https://itunes.apple.com/search?term=Dinos&entity=songs&limit=200&sort=recent&country=fr
    //file_put_contents(LOG_FILE, "\n\n\nFetching artist {$objArtist->name}, id {$objArtist->id}\n", FILE_APPEND);

    // Recupération des albums sur l'API
    $api = new api($artist->getId());
    $newEntities = $api->update($artist->getLastUpdate(), true);

}

function editLastUpdated($days = 7, $id_user = 0) {
    $db = new db;
    return $db->editLastUpdated($days, $id_user);
}

function logRefresh($type = "")
{
    $db = new db;
    return $db->logRefresh($type);
}

function logMail($description, $id_user)
{
    $db = new db;
    return $db->logMail($description, $id_user);
}

function getLastRefresh()
{
    $db = new db;
    return $db->getLastRefresh();
}

function getNotificationsStatus()
{
    $db = new db;
    return $db->getNotificationsStatus();
}

/**
 * Renvoie une date selon le format voulu (string : Y:m:d H:i:s|timestamp)
 * @param string|int $date
 * @param string|int $format ("string"|"integer,int,timestamp")
 * @return false|int|string
 */
function fixDate($date, $format = "string")
{
    $format = strtolower($format);
    switch ($format) {
        case "string":
            if (is_numeric($date))
                return date(DEFAULT_DATE_FORMAT, $date);
            return $date;
        case "string_time_no_sec":
            if (is_numeric($date))
                return date(DEFAULT_DATE_FORMAT_NO_SECS, $date);
            return $date;
        case "string_time":
            if (is_numeric($date))
                return date(DEFAULT_DATE_FORMAT_TIME, $date);
            return $date;
        case "integer":
        case "int":
        case "timestamp":
            if (!is_numeric($date))
                return strtotime($date);
            return $date;
        default:
            return $date;
    }
}

function fixTZDate($date)
{
    return str_replace("Z", " ", str_replace("T", " ", str_replace("07:00:00", "00:00:00", str_replace("08:00:00", "00:00:00", $date))));
}

/**
 * @param $d
 * @return mixed
 */
function getWeekDay($d)
{
    $weekdays = unserialize(WEEKDAYS_NAMES);
    return isset($weekdays[$d]) ? $weekdays[$d] : "error";
}

/**
 * @param int $m
 * @param bool $short
 * @return mixed
 */
function getMonth($m, $short = false)
{
    $monthNames = unserialize($short ? MONTHS_NAMES_SHORT : MONTHS_NAMES);

    $m = intval($m);
    return isset($monthNames[$m]) ?
        $monthNames[$m] :
        (1 <= $m && $m <= 12 ? strtolower(date("F", strtotime("01-$m-2000"))) : "error");
}

/**
 * Vérifie que l'utilisateur est connecté
 * L'adresse cible dans un cookie sinon et on le redirige vers la page de connexion
 */
function checkConnexion()
{
    if (!isConnected()) {
        if ($_SERVER && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
            $_COOKIE["redirect"] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            setcookie("redirect", "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
        header("location: login.php");
        exit;
    }
}

/**
 * Vérifie que l'utilisateur est connecté
 * @return bool
 */
function isConnected()
{
    return (isset($_SESSION) && !empty($_SESSION) && isset($_SESSION["id_user"]) && strlen(strval($_SESSION["id_user"])));
}


function getInitial($str)
{
    $tmp = explode(" ", preg_replace("/[^A-Za-z0-9$ ]/", '', $str));
    if (count($tmp) === 1)
        return strtoupper($tmp[0][0]);
    else {
        end($tmp);
        return strtoupper($tmp[0][0] . $tmp[key($tmp)][0]);
    }
}

function getArtistSVG($str)
{
    return '
    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 640 640"
         class="we-artwork__image artist-search-artwork-img">
        <defs>
            <linearGradient id="a" x1="50%" y1="0%" x2="50%" y2="100%">
                <stop offset="0%" stop-color="#A5ABB8"></stop>
                <stop offset="100%" stop-color="#848993"></stop>
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#a)"></rect>
        <text x="320" y="50%" dy="0.35em" font-size="250" fill="#fff" text-anchor="middle"
              font-family="SF Pro Display,Helvetica,Arial" font-weight="500">' . getInitial($str) . '
        </text>
    </svg>
    ';
}

function removeIntIndex($array)
{
    foreach ($array as $key => $value) {
        if (is_int($key)) {
            unset($array[$key]);
        }
    }
    return $array;
}

function writeJSON($name, $content)
{
    $fp = fopen($name, 'w');
    fwrite($fp, json_encode($content));
    fclose($fp);
}