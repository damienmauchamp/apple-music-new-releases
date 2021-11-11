<?php
//*/30  * * * * php global.php refresh= nodisplay= >/dev/null 2>&1
// delay=7

require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
$root = "";
global $news;
global $delay;

use AppleMusic\DB as db;

$db = new db;

foreach ($db->getUsersIDs() as $user) {
    $idUser = $user["id"];
    $_SESSION["id_user"] = $idUser;

	// reduce lastUpdated by a week
	if ($delay) {
	    $db->editLastUpdated($delay, $idUser);
	}

    $res = getAllNewScrappedReleases();
    $albums = $res && isset($res["albums"]) ? $res['albums'] : null;
    $songs = $res && isset($res["songs"]) ? $res['songs'] : null;
    logRefresh("no display --- $idUser");
    echo json_encode(true);
    //exit;
}
$idUser = null;
$_SESSION = null;
exit;