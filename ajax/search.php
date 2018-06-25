<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use AppleMusic\API as api;

$term = $_GET["q"];

$api = new api;
$artists = $api->searchArtist(str_replace(" ", "+", $term));
echo json_encode($artists);