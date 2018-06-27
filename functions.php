<?php

use AppleMusic\DB as db;
use AppleMusic\API as api;
use AppleMusic\Artist as Artist;
use AppleMusic\Album as Album;

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
}



// Récupère tous les artistes
function getAllNewReleases()
{
    $db = new db;
    /** @var Artist $artist */
    foreach (json_decode($db->getUsersArtists()) as $artist) {
        getArtistRelease($artist);
//        break;
    }
}

/**
 * @param $objArtist
 */
function getArtistRelease($objArtist)
{
    $db = new db;
    // Artiste
    $artist = new Artist($objArtist->id);
    $artist->setName($objArtist->name);
    $artist->setLastUpdate($objArtist->lastUpdate);

    // Recupération des albums sur l'API
    $api = new api($artist->getId());
    $newAlbums = $api->update($artist->getLastUpdate());
    $artist->setAlbums($newAlbums);
//    print_r($artist->getAlbums());exit;

    // Mise en BD des nouveaux albums
    /** @var Album $album */
    foreach ($artist->getAlbums() as $album) {
        // Ajout de l'album à la BD
        $album->addAlbum($artist->getId());
        $artist->update();
        echo $album->toString();
    }
}