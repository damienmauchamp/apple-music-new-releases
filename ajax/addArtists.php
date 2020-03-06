<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";

use AppleMusic\Artist as Artist;

$artists = isset($_GET["artists"]) ? $_GET["artists"] : null;

$result = [];
foreach ($artists as $id) {

	echo "{$id}//";

    $artist = new Artist($id);
    $artist->fetchArtistInfo();

    $result[] = [
   		'id' => $id,
   		'name' => $artist->getName(),
   		'res' => $artist->addArtist(),
    ];
}

exit(json_encode($result));