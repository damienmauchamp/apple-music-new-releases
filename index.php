<? require_once "start.php";
$root = "";
$display = isset($_GET["page"]) ? $_GET["page"] : null;
$theme = "is-music-theme";
?>
<!DOCTYPE html>
<html>
<head>
</head>
<? include "inc/meta.php"; ?>
<body class="<?= $theme ?>">


<select class="test" name="artists[]" multiple="multiple" style="width:100%"></select>
<button class="testGo">Ajouter</button>

<!--<form action="." method="post">-->
<!--    <select class="test" name="artists[]" multiple="multiple" style="width:100%">-->
<!--    </select>-->
<!--    <input type="submit" value="GO">-->
<!--</form>-->


<?php
require __DIR__ . '/vendor/autoload.php';
?>

<section class="l-content-width main-header">
    <h1 class="section__headline--hero">Test</h1>
</section>

<?

/** Page d'accueil : */
getAllAlbums($display);

/*
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
