<?
require __DIR__ . '/vendor/autoload.php';
require_once "start.php";
$root = "";

?>
<!DOCTYPE html>
<html>
<head>
    <? include "inc/meta.php"; ?>
</head>
<body class="<?= $theme ?>">
<? include "inc/nav.php"; ?>

<section class="section-add-artists">
    <select class="add-artists" id="artists[]" name="artists[]" multiple="multiple" style="width: 100%;"></select>
    <label for="artists[]" class="add-artists-label --invisible"></label>
    <div class="add-artists-label-after">Ajouter</div>
</section>

<section class="main-header l-content-width">
    <h1 class="section__headline--hero"><?= $news ? "Refresh" : "Releases" ?></h1>
</section>

<? if ($news) : ?>

    <section class="l-content-width section">
        <h2 class="section__headline">
            New albums
        </h2>

        <div class="l-row">
            <? getAllNewReleases(); ?>
        </div>

        <div id="ember1200" style="display: none;"
             class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
    </section>

<? else : getAllAlbums($page); endif ?>
</body>
</html>
