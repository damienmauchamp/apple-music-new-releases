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
    logRefresh("no display --- $idUser");
    $res = getAllNewReleases();
    $albums = $res["albums"];
    $songs = $res["songs"];
    echo json_encode(true);
    //exit;
}
$idUser = null;
$_SESSION = null;
exit;