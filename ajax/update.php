<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";
global $debug;
$_VARS = $debug ? $_GET : $_POST;

//echo json_encode($_VARS);exit;

use AppleMusic\Album;
use AppleMusic\API as api;
use AppleMusic\Artist;
use AppleMusic\DB as db;

$function = isset($_VARS["f"]) ? intval($_VARS["f"]) : null;
$scrapped = isset($_VARS["scrapped"]) ? $_VARS["scrapped"] === 'true' || (int) $_VARS["scrapped"] === '1' : false;
$idArtist = isset($_VARS["id"]) ? $_VARS["id"] : null;

// 1 : artistNewReleases(idArtist)
if ($function === 1) {
    $artist = new Artist($idArtist);
    getArtistReleases($idArtist);
} else if ($function === 2) {
    $artist = new Artist($idArtist);
    $tmp = str_replace('-', '/', date("Y-m-d"));
    getArtistReleases($idArtist, date(DEFAULT_DATE_FORMAT_TIME));
} else if ($function === 3) {
    $db = new db;
    $artists = $db->getUsersArtists();
    header("Content-type:application/json");
    echo json_encode($artists);
} else if ($function === 4) {
    $infos = isset($_VARS["artist"]) ? $_VARS["artist"] : null;
    $artist = (object)array(
        "id" => $infos["id"],
        "name" => $infos["name"],
        "lastUpdate" => $infos["lastUpdate"]
    );

    if ($scrapped) {
        getArtistScrappedRelease($artist, "albums");
    } else {
        getArtistRelease($artist, "albums");
    }
} else if ($function === 5) { // notification update
    $notif = isset($_VARS["notif"]) ? ($_VARS["notif"] === "true" ? true : false) : null;
    header("Content-type:application/json");
    if ($notif === null) {
        echo json_encode(false);
        return;
    }
    $db = new db;
    echo json_encode($db->setNotificationsStatus($notif));
}
// get one artist
else if ($function === 6) {
    $idArtist = isset($_VARS["idArtist"]) ? $_VARS["idArtist"] : 0;
    $db = new db;
    $artist = $db->getUserArtist($idArtist);
    header("Content-type:application/json");
    echo json_encode($artist);
}



/**
 * @param $idArtist
 * @param bool|string $date
 */
function getArtistReleases($idArtist, $date = false)
{
    $artist = new Artist($idArtist);
    $artist->getArtistDB();

    // Recupération des albums sur l'API
    $api = new api($artist->getId());
    $newAlbums = $api->update($artist->getLastUpdate());
    $artist->setAlbums($newAlbums['albums']);
    $artist->setSongs($newAlbums['songs']);

    // json
    header("Content-type:application/json");
    if ($artist->getAlbums()) {
        echo $artist->toJSON();
    } else {
        echo json_encode(array("response" => false));
    }
    $artist->update($date);

    // Mise en BD des nouveaux albums
    if ($artist->getAlbums()) {
        /** @var Album $album */
        foreach ($artist->getAlbums() as $album) {
            if ($album && $album instanceof Album) {
//                $entity = is_array($album) ? $album[0] : $album;
//                $entity->addAlbum($artist->getId());
                $album->addAlbum($artist->getId());
            }
        }
    }
}