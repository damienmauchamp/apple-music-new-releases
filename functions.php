<?php

use AppleMusic\DB as db;
use AppleMusic\API as api;
use AppleMusic\Artist as Artist;
use AppleMusic\Album as Album;

function displayAlbums($albums)
{
    /** @var Album $album */
    foreach ($albums as $album) {
        echo Album::withArray(Album::objectToArray($album))->toString("albums");
    }
}

// Dernières sorties depuis actualisation, dans la BD
function getAllAlbums($display = "artists")
{
    $db = new db;
    $releases = $db->getUserReleases();
    $artists = array();

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
    $newAlbums = $api->update($artist->getLastUpdate());
    $artist->setAlbums($newAlbums);

    $albums = $artist->getAlbums();
    // Mise en BD des nouveaux albums
    /** @var Album $album */
    foreach ($albums as $album) {
        // Ajout de l'album à la BD
        $album->addAlbum($artist->getId());
//        $artist->update();
        echo $nodisplay ? null : $album->toString($display);
    }
    return $albums;
}

function logRefresh($type = "") {
    $db = new db;
    return $db->logRefresh($type);
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