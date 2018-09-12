<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";
$debug = true;
$_VARS = $debug ? $_GET : $_POST;

use AppleMusic\Album;
use AppleMusic\API as api;
use AppleMusic\Artist;
use AppleMusic\DB as db;

$function = isset($_VARS["f"]) ? intval($_VARS["f"]) : null;
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
    getArtistRelease($artist, "albums");
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
    $artist->setAlbums($newAlbums);

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
            if ($album)
                $album->addAlbum($artist->getId());
        }
    }
}