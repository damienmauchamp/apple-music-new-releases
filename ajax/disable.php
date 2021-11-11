<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";

header("Content-Type: application/json");

use AppleMusic\DB as db;

$db = new db();

$id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
$type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);

if (!$id || !$type) {
	echo json_encode(["error" => "Missing parameters"]);
	exit;
}

if (!in_array($type, ['album', 'song'])) {
	echo json_encode(["error" => "Invalid type"]);
	exit;
}

$result = $type === 'album' ? $db->disableAlbum($id) : $db->disableSong($id);
echo json_encode(['result' => $result]);
