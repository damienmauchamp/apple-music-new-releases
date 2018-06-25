<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use AppleMusic\Artist as Artist;

$artists = isset($_GET["artists"]) ? $_GET["artists"] : null;

foreach ($artists as $id) {
    $artist = new Artist($id);
    $artist->fetchArtistInfo();
    $artist->addArtist();
}