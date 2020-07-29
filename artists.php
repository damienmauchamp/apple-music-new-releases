<?
require __DIR__ . '/vendor/autoload.php';
require_once(__DIR__ . "/start.php");
$root = "";

checkConnexion();

use AppleMusic\DB as db;

$db = new db;
$artists = json_decode($db->getUsersArtists());
?>
<!DOCTYPE html>
<html>
<head>
    <? include "inc/meta.php"; ?>
    <style>
        .artist-artwork {
            width: 44px;
        }

        .edit-artist {
            color: #da0f47;
        }

        button.update-artist {
            margin-right: 1vw;
        }

        .table__row__artwork {
            width: 1%;
        }
    </style>
</head>
<body class="<?= $theme ?>">
<div class="main">
    <? include "inc/nav.php"; ?>

    <section class="main-header l-content-width section" style="border-top:none">

        <h2 class="section__headline">
            Ajouts d'artistes
        </h2>
        <div class="section-add-artists">
            <select class="add-artists" id="artists[]" name="artists[]" multiple="multiple"
                    style="width: 100%;"></select>
            <label for="artists[]" class="add-artists-label --invisible"></label>
            <div class="add-artists-label-after">Ajouter</div>
        </div>
    </section>

    <section class="l-content-width section section--bordered">
        <div class="l-row">
            <div class="l-column small-12">
                <h2 class="section__headline">
                    Mes artistes
                </h2>

                <table class="table table--see-all my-artists">
                    <thead class="table__head">
                    <tr>
                        <th class="table__head__heading--artwork"></th>
                        <th class="table__head__heading table__head__heading--artist large-show-tablecell">
                            ARTISTE
                        </th>
                        <th class="table__head__heading table__head__heading--duration"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($artists as $artist) : ?>
                        <tr id="artist-<?= $artist->id ?>"
                            class="table__row  we-selectable-item is-available we-selectable-item--allows-interaction ember-view">

                            <td class="table__row__artwork">
                                <picture
                                        class="artist-artwork we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork--round we-artwork ember-view">
                                    <?= getArtistSVG($artist->name) ?>
                                </picture>
                            </td>

                            <td class="table__row__name">
                                <a href="//itunes.apple.com/fr/artist/<?= $artist->id ?>" target="_blank"
                                   class="table__row__link targeted-link targeted-link--no-monochrome-underline">
                                    <div class="spread">
                                        <span id="ember995"
                                              class="table__row__headline targeted-link__target we-truncate we-truncate--single-line ember-view">
                                            <?= $artist->name ?>
                                        </span>
                                    </div>
                                </a>
                                <div>
                            </td>

                            <td class="table__row__duration edit-artist">
                                <button class="update-artist" data-artist-id="<?= $artist->id ?>" onclick="updateArtist('<?= $artist->id ?>');">UPDATE</button>
                                <button class="rm-artist" data-artist-id="<?= $artist->id ?>">✕</button>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
</body>
</html>

