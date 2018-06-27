<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use AppleMusic\Album;
use AppleMusic\API as api;
use AppleMusic\Artist;
use AppleMusic\DB as db;

//$function = isset($_POST["f"]) ? intval($_POST["f"]) : null;
$function = isset($_GET["f"]) ? intval($_GET["f"]) : null;

// 1 : artistNewReleases(idArtist)
if ($function === 1) {
//    $idArtist = isset($_POST["id"]) ? $_POST["id"] : null;
    $idArtist = isset($_GET["id"]) ? $_GET["id"] : null;
    $artist = new Artist($idArtist);
//    $artist->fetchArtistInfo();
    getArtistReleases($idArtist);
} else if ($function === 2) {
    $idArtist = isset($_GET["id"]) ? $_GET["id"] : null;
    $artist = new Artist($idArtist);
    $tmp = str_replace('-', '/', date("Y-m-d"));
    getArtistReleases($idArtist, date('Y-m-d'));
} else {
    echo "L";
}


//
//$api = new api;
//$artists = $api->searchArtist(str_replace(" ", "+", $term));
//echo json_encode($artists);


/**
 * @param $idArtist
 * @param bool|string $date
 */
function getArtistReleases($idArtist, $date = false)
{
    $db = new db;
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
    /** @var Album $album */
    foreach ($artist->getAlbums() as $album) {
        $album->addAlbum($artist->getId());
//        print_r($album->addAlbum($artist->getId()));
//        var_dump($album);
    }
}