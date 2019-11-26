<?php
require __DIR__ . '/vendor/autoload.php';
require_once "start.php";

if ($debug) :
    $rustart = getrusage();
    $time_start = microtime(true);
endif;

checkConnexion();
$root = "";
global $news;

if (isset($_POST["load_songs"]) && $_POST["load_songs"]) {
    header("Content-type:text/html");
    displaySongs(getAllSongs());
    exit;
}


/**
 * TODO : page de logs
 *
 * artists that needs update
 * var needToUpdateIds = [];
 * $("#new-albums .album").each(function() {
 * needToUpdateIds.push($(this).data("amArtistId"));
 * });
 * needToUpdateIds.forEach(function(x) {
 * console.log("'" + x + "', ");
 * });
 *
 * UPDATE users_artists
 * SET lastUpdate = NOW()
 * WHERE idUser = 1 AND idArtist IN
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
            <h1 class="section__headline--hero"><?= $news ? "Mise à jour" : "Nouvelles Sorties" ?></h1>

            <div id="maj-cont">Dernière MAJ : <?= getLastRefresh(); ?></div>
            <div id="mail-alert-cont">
                <label for="mail-alert">
                    <input type="checkbox"
                           id="mail-alert" <?= getNotificationsStatus() ? "checked=\"checked\"" : "" ?>/>
                    Notifications par mail</label>

                <i class="fa fa-cog" id="settings"></i>
            </div>
        </section>

        <? if ($news) : ?>

            <section class="l-content-width section section--bordered">
                <h2 class="section__headline">
                    Nouveaux albums
                </h2>

                <div class="l-row" id="new-albums">

                    <? if (!$full) :
                        logRefresh(); ?>
                        <script>getNewReleases();</script>
                        <div class="spinner-cont">
                            <div id="loading-spinner"
                                 class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
                        </div>
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
            $songs = false;//getAllSongs();
            //var_dump($songs);
            ?>
            <section class="l-content-width section section--bordered">
                <div class="l-row">
                    <div class="l-column small-12">
                        <h2 class="section__headline">
                            Toutes les chansons
                        </h2>
                        <table class="table table--see-all" id="song-table-table">
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
                                <th class="table__head__heading table__head__heading--duration">SORTIE</th>
                            </tr>
                            </thead>
                            <tbody id="song-table-tbody">
                            <? //displaySongs($songs)
                            ?>
                            </tbody>
                        </table>
                        <div class="spinner-cont">
                            <div id="loading-spinner"
                                 class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
                        </div>
                    </div>
                </div>
            </section>

            <? // songs start
            if ($songs) : ?>
                <section class="l-content-width section section--bordered">
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
                                    <th class="table__head__heading table__head__heading--duration">SORTIE</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? displaySongs($songs) ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            <? // songs end
            endif; ?>

            <? // albums start
            if ($albums) : ?>
                <section class="artist l-content-width section section--bordered">
                    <h2 class="section__headline">
                        Tous les albums
                    </h2>
                    <div class="l-row">
                        <? displayAlbums($albums) ?>
                    </div>
                </section>
            <? // albums end
            endif; ?>

        <? endif; ?>
    </div>

    <script>
        var load_songs = function () {
            $.ajax({
                url: "index.php",
                method: "POST",
                data: {load_songs: true},
                success: function(data) {
                    $("#song-table-tbody").append(data);
                    $("#loading-spinner").hide();
                }
            });
        }();
    </script>

    <ul class='custom-menu'>
        <li data-action="open-itunes"><a href="#">Afficher sur iTunes</a></li>
        <li data-action="open-browser"><a href="#" target="_blank">Afficher dans le navigateur</a></li>
    </ul>

    </body>
    </html>
<?
if ($debug) :
    function rutime($ru, $rus, $index)
    {
        return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
            - ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
    }

    $ru = getrusage();
    echo "This process used " . rutime($ru, $rustart, "utime") .
        " ms for its computations\n";
    echo "It spent " . rutime($ru, $rustart, "stime") .
        " ms in system calls\n";
    $time_end = microtime(true);
    //dividing with 60 will give the execution time in minutes otherwise seconds
    $execution_time = ($time_end - $time_start) / 60;
    //execution time of the script
    echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
endif;