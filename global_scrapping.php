<?php
//5 0 * * 5 php global_scrapping.php refresh= nodisplay= >/dev/null 2>&1
// delay=7

require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
$root = "";
global $news;
global $delay;

use AppleMusic\DB as db;

$db = new db;

$idArtist = $_GET['idArtist'] ?? $argv[3] ?? null;
if (!$idArtist || !preg_match('/^[0-9]+$/', $idArtist)) {
    $idArtist = null;
}

foreach ($db->getUsersIDs() as $user) {
    $idUser = $user["id"];
    logRefresh("scrapping --- $idUser");
    $_SESSION["id_user"] = $idUser;

    // reduce lastUpdated by a week
    if ($delay) {
        $db->editLastUpdated($delay, $idUser);
    }

    $res = getAllNewScrappedReleases($idArtist);
    $albums = $res && isset($res["albums"]) ? $res['albums'] : null;
    $songs = $res && isset($res["songs"]) ? $res['songs'] : null;
    echo json_encode(true);
    //exit;
}
$idUser = null;
$_SESSION = null;
exit;
