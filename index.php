<? require_once "start.php";
$root = ""; ?>
<!DOCTYPE html>
<html>
<head>
</head>
<? include "inc/meta.php" ?>
<body>


<select class="test" name="artists[]" multiple="multiple" style="width:100%"></select>
<button class="testGo">Ajouter</button>

<!--<form action="." method="post">-->
<!--    <select class="test" name="artists[]" multiple="multiple" style="width:100%">-->
<!--    </select>-->
<!--    <input type="submit" value="GO">-->
<!--</form>-->


<?php
require __DIR__ . '/vendor/autoload.php';

getAllAlbums();

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
