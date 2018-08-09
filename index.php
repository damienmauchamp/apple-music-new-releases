<?
require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
$root = "";
global $news;

/**
 * TODO : page de logs
 * TODO : liste d'artistes
 * TODO : multi users (plus tard)
 */

if ($news && $nodisplay) {
    logRefresh("no display");
    $albums = getAllNewReleases();
    echo json_encode(true);
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <? include "inc/meta.php"; ?>
</head>
<body class="<?= $theme ?>">
<div class="main">
    <? include "inc/nav.php"; ?>

    <section class="main-header l-content-width section" style="border-top:none">
        <h1 class="section__headline--hero"><?= $news ? "Refresh" : "Releases" ?></h1>

        <h2 class="section__headline">
            Ajouts d'artistes
        </h2>
        <div class="section-add-artists">
            <select class="add-artists" id="artists[]" name="artists[]" multiple="multiple"
                    style="width: 100%;"></select>
            <label for="artists[]" class="add-artists-label --invisible"></label>
            <div class="add-artists-label-after">Ajouter</div>
        </div>

        <div>MAJ : <?= getLastRefresh(); ?></div>
    </section>

    <? if ($news) : ?>

        <section class="l-content-width section">
            <h2 class="section__headline">
                New albums
            </h2>

            <div class="l-row" id="new-albums">

                <? if (!$full) :
                    logRefresh(); ?>
                    <script>getNewReleases();</script>
                    <div id="loading-spinner"
                         class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
                <? else :
                    logRefresh("full");
                    $albums = getAllNewReleases();
                endif ?>

            </div>

        </section>

    <? else : $albums = getAllAlbums(); ?>

        <section class="artist l-content-width section section--bordered">
            <h2 class="section__headline">
                All albums
            </h2>
            <div class="l-row">
                <? displayAlbums($albums) ?>
            </div>
        </section>

    <? endif; ?>
</div>
</body>
</html>
