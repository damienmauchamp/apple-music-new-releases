<?
require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
$root = "";
$display = isset($_GET["page"]) ? $_GET["page"] : null;
$theme = "is-music-theme";
$news = isset($_GET["refresh"]) && $_GET["refresh"];
?>
<!DOCTYPE html>
<html>
<head>
    <? include "inc/meta.php"; ?>
</head>
<body class="<?= $theme ?>">


<select class="test" name="artists[]" multiple="multiple" style="width:100%"></select>
<button class="testGo">Ajouter</button>

<section class="l-content-width main-header">
    <h1 class="section__headline--hero">Test</h1>
    <? $news ? getAllNewReleases() : null; ?>
</section>
<? (!$news) ? getAllAlbums($display) : null; ?>

<?php
//
//if (isset($_POST["refresh"]) && $_POST["refresh"] || true) {
//    getAllNewReleases();
//} else {
//    /** Page d'accueil : */
//    getAllAlbums($display);
//}
?>

<? /*
 * Page d'accueil
 *      - anciennes sorties;
 *      - getAllAlbums();
 *
 * Récup de tous les nouveaux albums sur l'APi
 *      - getArtistRelease($a);
 *
 * Récup de tous les nouveaux albums d'un artiste sur l'APi
 *      - getAllNewReleases();
 */
?>
</body>
</html>
