<?php
//*/30  * * * * php global.php refresh= nodisplay= >/dev/null 2>&1
// delay=7

require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
$root = "";
global $news;
global $delay;

use AppleMusic\DB as db;
use AppleMusic\User;

$db = new db();

$idArtist = $_GET['idArtist'] ?? $argv[3] ?? null;
// dd($idArtist, $argv);
if (!$idArtist || !preg_match('/^[0-9]+$/', $idArtist)) {
	$idArtist = null;
}

// print_r([
//     '_GET' => $_GET,
//     '$argv' => $argv ?? null,
//     'idArtist' => $idArtist,
// ]);
// exit();

foreach ($db->getUsersIDs() as $user) {
	$idUser = $user["id"];
	logRefresh("no display --- $idUser");
	$_SESSION["id_user"] = $idUser;

	// reduce lastUpdated by a week
	if ($delay) {
		$db->editLastUpdated($delay, $idUser);
	}

	$res = getAllNewReleases($idArtist);
	$albums = $res && isset($res["albums"]) ? $res['albums'] : null;
	$songs = $res && isset($res["songs"]) ? $res['songs'] : null;
	echo json_encode(true);
	//exit;
}
$idUser = null;
$_SESSION = null;
exit;
