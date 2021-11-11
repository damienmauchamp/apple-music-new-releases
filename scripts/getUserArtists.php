<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";

use AppleMusic\DB as db;

$user = isset($_GET["user"]) ? $_GET["user"] : "";
header("Content-Type: application/json");

if (!$user) {
	echo json_encode(null);
	return;
}

$db = new db;
$artists = array(
	"results" => 0,
	"artists" => array(),
);
foreach (json_decode($db->getUsersArtists($user, false)) as $item) {
	$artists["artists"][] = array(
		"id" => $item->id, 
		"name" => $item->name
	);
}
$artists["results"] = count($artists["artists"]);
echo json_encode($artists);