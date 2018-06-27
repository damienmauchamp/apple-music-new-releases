<?php

use AppleMusic\DB as db;
use AppleMusic\API as api;
use AppleMusic\Artist as Artist;
use AppleMusic\Album as Album;

// Récupère tous les artistes
function getAllNewReleases()
{
    $db = new db;
    foreach (json_decode($db->getUsersArtists()) as $artist) {
        getArtistRelease($artist);
        break;
        // Affichage artist->toString()
        //  - infos artiste
        //  - des nouveaux albums album->toString()
        //  - des anciens albums en base
//    break;
    }
}

/**
 * @param $a
 */
function getArtistRelease($a)
{
    // Artiste
    $artist = new Artist($a->id);
    $artist->setName($a->name);
    $artist->setLastUpdate($a->lastUpdate);
    var_dump($artist);

    // Recupération des albums sur l'API
    $api = new api($artist->getId());
    $newAlbums = $api->update($artist->getLastUpdate());
    $artist->setAlbums($newAlbums);

    // Mise en BD des nouveaux albums
    /** @var Album $album */
    foreach ($artist->getAlbums() as $album) {
        var_dump($album->addAlbum($artist->getId()));
        var_dump($album);
//    $db->updated($id);
    }
}

// Dernières sorties depuis actualisation, dans la BD
function getAllAlbums()
{
    $db = new db;
    $releases = $db->getUserReleases();
    $artists = array();

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