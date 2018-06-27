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