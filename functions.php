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

// Dernières sorties depuis actualisation, dans la BD
function getAllAlbums($display = "artists")
{
    $db = new db;
    $releases = $db->getUserAlbums();
    $artists = array();

    if (!$releases)
        return json_decode($releases);

    switch ($display) {
        case "albums":
            ?>
            <section class="artist l-content-width section">
                <div class="section-header section__headline">
                    <h2 class="section-title">Tous les albums</h2>
                </div>

                <div class="section-body l-row">
                    <?
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
                    ?>
                </div>
            </section>
            <?
            break;
        case "artists":
        default:
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
            break;
    }
    return json_decode($releases);
}

function getAllSongs()
{
    $db = new db;
    $releases = $db->getUserSongs();
    $artists = array();

    if ($releases) {
        foreach (json_decode($releases) as $r) {
            $artistId = $r->idArtist;
            $song = Song::withArray(Song::objectToArray($r));
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

    return json_decode($releases);
}


// Récupère tous les artistes
function getAllNewReleases()
{
    $db = new db;
    $releases = array();
    /*$removal =*/
    $db->removeOldAlbums();
    foreach (json_decode($db->getUsersArtists()) as $artist) {
        $releases[] = getArtistRelease($artist);
//        break;
    }
    return $releases;
}

function removeOldAlbums($days = 14)
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

    // Recupération des albums sur l'API
    $api = new api($artist->getId());
    $newEntities = $api->update($artist->getLastUpdate());

    $artist->setAlbums($newEntities["albums"]);
    $artist->setSongs($newEntities["songs"]);

    $albums = $artist->getAlbums();
    $songs = $artist->getSongs();
    // Mise en BD des nouveaux albums & chansons

    /** @var Album $album */
    foreach ($albums as $album) {
        // Ajout de l'album à la BD
        $album->addAlbum($artist->getId());
//        $artist->update();
        echo $nodisplay ? null : $album->toString($display);
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

function logRefresh($type = "")
{
    $db = new db;
    return $db->logRefresh($type);
}

function getLastRefresh()
{
    $db = new db;
    return $db->getLastRefresh();
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
    return str_replace("Z", " ", str_replace("T", " ", str_replace("07:00:00", "00:00:00", $date)));
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
        $_COOKIE["redirect"] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        setcookie("redirect", "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
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