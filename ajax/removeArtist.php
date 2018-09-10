<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";

use AppleMusic\Artist as Artist;

$id = isset($_GET["artist"]) ? $_GET["artist"] : null;
$artist = new Artist($id);
echo json_encode($artist->removeUsersArtist());