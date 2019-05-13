<?php

require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
$root = "";
global $news;

use AppleMusic\DB as db;

$db = new db;
foreach ($db->getUsersIDs() as $user) {
    $idUser = $user["id"];
    $_SESSION["id_user"] = $idUser;
    $res = getAllNewReleases();
    $albums = $res && isset($res["albums"]) ? $res['albums'] : null;
    $songs = $res && isset($res["songs"]) ? $res['songs'] : null;
    logRefresh("no display --- $idUser");
    echo json_encode(true);
    //exit;
}
$idUser = null;
$_SESSION = null;
exit;