<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use AppleMusic\Album;
use AppleMusic\API as api;
use AppleMusic\Artist;
use AppleMusic\DB as db;

$function = isset($_POST["f"]) ? $_POST["f"] : null;

// 1 : artistNewReleases(idArtist)
if ($function === 1) {
    $idArtist = isset($_POST["id"]) ? $_POST["id"] : null;
}



//
//$api = new api;
//$artists = $api->searchArtist(str_replace(" ", "+", $term));
//echo json_encode($artists);


// Récupère tous les artistes
function getAllNewReleases()
{
    $db = new db;
    /** @var Artist $artist */
    foreach (json_decode($db->getUsersArtists()) as $artist) {
        getArtistRelease($artist->getId());
        break;
    }
}

/**
 * @param $idArtist
 */
function getArtistRelease($idArtist)
{
    $db = new db;
    // Artiste
    $artist = new Artist($idArtist->id);
    $artist->setName($idArtist->name);
    $artist->setLastUpdate($idArtist->lastUpdate);
    var_dump($artist);

    // Recupération des albums sur l'API
    $api = new api($artist->getId());
    $newAlbums = $api->update($artist->getLastUpdate());
    $artist->setAlbums($newAlbums);

    // Mise en BD des nouveaux albums
    /** @var Album $album */
    foreach ($artist->getAlbums() as $album) {
        $album->addAlbum($artist->getId());
        $artist->update();
//        var_dump($album->addAlbum($artist->getId()));
//        var_dump($album);
    }
}