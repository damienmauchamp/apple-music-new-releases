<?
require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
checkConnexion();
$root = "";
global $news;

/**
 * TODO : page de logs
 * TODO : liste d'artistes
 */

if ($news && $nodisplay) {
    logRefresh("no display");
    $res = getAllNewReleases();
    $albums = $res["albums"];
    $songs = $res["songs"];
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
        <h1 class="section__headline--hero"><?= $news ? "Mise à jour" : "Sorties" ?></h1>

        <h2 class="section__headline">
            Ajouts d'artistes
        </h2>
        <div class="section-add-artists">
            <select class="add-artists" id="artists[]" name="artists[]" multiple="multiple"
                    style="width: 100%;"></select>
            <label for="artists[]" class="add-artists-label --invisible"></label>
            <div class="add-artists-label-after">Ajouter</div>
        </div>

        <div>Dernière MAJ : <?= getLastRefresh(); ?></div>
    </section>

    <? if ($news) : ?>

        <section class="l-content-width section">
            <h2 class="section__headline">
                Nouveaux albums
            </h2>

            <div class="l-row" id="new-albums">

                <? if (!$full) :
                    logRefresh(); ?>
                    <script>getNewReleases();</script>
                    <div id="loading-spinner"
                         class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
                <? else :
                    logRefresh("full");
                    $res = getAllNewReleases();
                    $albums = $res["albums"];
                    $songs = $res["songs"];
                endif ?>

            </div>

        </section>

    <? else :
        $albums = getAllAlbums();
        $songs = getAllSongs();
        //var_dump($songs);
        ?>

        <section class="artist l-content-width section section--bordered">
            <h2 class="section__headline">
                Tous les albums
            </h2>
            <div class="l-row">
                <? displayAlbums($albums) ?>
            </div>
        </section>


        <section class="l-content-width section">
            <div class="l-row">
                <div class="l-column small-12">
                    <h2 class="section__headline">
                        Toutes les chansons
                    </h2>
                    <table class="table table--see-all">
                        <thead class="table__head">
                        <tr>
                            <th class="table__head__heading--artwork"></th>
                            <th class="table__head__heading table__head__heading--song">TITRE</th>
                            <th class="table__head__heading table__head__heading--artist small-hide large-show-tablecell">
                                ARTISTE
                            </th>
                            <th class="table__head__heading table__head__heading--album small-hide medium-show-tablecell">
                                ALBUM
                            </th>
                            <th class="table__head__heading table__head__heading--duration"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <? displaySongs($songs) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    <? endif; ?>
</div>
</body>
</html>
