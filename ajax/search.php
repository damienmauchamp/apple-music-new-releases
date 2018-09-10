<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";

use AppleMusic\API as api;

$term = isset($_GET["q"]) ? $_GET["q"] : "";
header("Content-Type: application/json");

$api = new api;
$artists = $api->searchArtist(str_replace(" ", "+", $term));
echo json_encode($artists);