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

<? if ($mobile && false) { ?>

    <section class="artist l-content-width section section--bordered">
        <div class="section__nav">
            <h2 class="section__headline">Playlists de l'artiste</h2>
        </div>
        <div class="l-row l-row--peek">

            <a id="ember880"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember881"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/146x146sr.jpg 1x,https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/292x292sr.jpg 2x,https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/438x438sr.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/158x158sr.jpg 1x,https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/316x316sr.jpg 2x,https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/474x474sr.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/200x200sr.jpg 1x,https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/400x400sr.jpg 2x,https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/600x600sr.jpg 3x">
                    <img src="https://is5-ssl.mzstatic.com/image/thumb/Features128/v4/65/2e/1c/652e1cc9-a929-606b-8e60-0b3ba8a190f6/source/200x200sr.jpg"
                         style="background-color: #121212;" class="we-artwork__image ember881"
                         alt="">
                    <style>
                        .ember881, #ember881::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember881, #ember881::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember881, #ember881::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>
                <!---->
                <h3 class="we-lockup__title">
                    <div class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                        A$AP Rocky : les indispensables
                    </div>
                </h3>
                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2018</h4>


                <!----><!----></a>

                <!---->
<!--                <h3 class="we-lockup__title  icon icon-after icon-explicit">-->
<!--                    <div id="ember796" class="we-truncate targeted-link__target we-truncate--single-line ember-view">-->
<!--                        TESTING-->
<!--                    </div>-->
<!--                </h3>-->

<!--                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2018</h4>-->
                <!---->


            <a id="ember887"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;pl.c3e13a7606b1430ab4d39e89d68bff03&quot;,&quot;locationType&quot;:&quot;shelfPlaylists&quot;,&quot;locationPosition&quot;:1}"
               href="https://itunes.apple.com/fr/playlist/a%24ap-rocky-aller-plus-loin/pl.c3e13a7606b1430ab4d39e89d68bff03"
               data-test-we-lockup-id="pl.c3e13a7606b1430ab4d39e89d68bff03"
               data-test-we-lockup-kind="playlist"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember888"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/146x146sr.jpg 1x,https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/292x292sr.jpg 2x,https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/438x438sr.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/158x158sr.jpg 1x,https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/316x316sr.jpg 2x,https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/474x474sr.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/200x200sr.jpg 1x,https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/400x400sr.jpg 2x,https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/600x600sr.jpg 3x">
                    <img src="https://is4-ssl.mzstatic.com/image/thumb/TMM3YslkRfbTY58MHMJyBA/200x200sr.jpg"
                         style="background-color: #0d0e10;" class="we-artwork__image ember888"
                         alt="">
                    <style>
                        .ember888, #ember888::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember888, #ember888::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember888, #ember888::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title ">
                    <div id="ember892"
                         class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                        A$AP Rocky&nbsp;: aller plus loin
                    </div>
                </h3>

                <!----><!----></a>


            <a id="ember894"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;pl.813196adc0fb4520b63194ad326ebdb6&quot;,&quot;locationType&quot;:&quot;shelfPlaylists&quot;,&quot;locationPosition&quot;:2}"
               href="https://itunes.apple.com/fr/playlist/featuring-a%24ap-rocky/pl.813196adc0fb4520b63194ad326ebdb6"
               data-test-we-lockup-id="pl.813196adc0fb4520b63194ad326ebdb6"
               data-test-we-lockup-kind="playlist"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember895"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/146x146sr.jpg 1x,https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/292x292sr.jpg 2x,https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/438x438sr.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/158x158sr.jpg 1x,https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/316x316sr.jpg 2x,https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/474x474sr.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/200x200sr.jpg 1x,https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/400x400sr.jpg 2x,https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/600x600sr.jpg 3x">
                    <img src="https://is3-ssl.mzstatic.com/image/thumb/Features128/v4/2f/86/9e/2f869e18-5687-d9e2-5e1f-f77650713777/source/200x200sr.jpg"
                         style="background-color: #c1a361;" class="we-artwork__image ember895"
                         alt="">
                    <style>
                        .ember895, #ember895::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember895, #ember895::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember895, #ember895::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title ">
                    <div id="ember899"
                         class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                        Featuring A$AP Rocky
                    </div>
                </h3>

                <!----><!----></a>


        </div>
    </section>
    <? exit;
} ?>
<? if ($mobile && false) { ?>
    <section class="artist l-content-width section section--bordered">
        <div class="section__nav">
            <h2 class="section__headline">Albums</h2>
            <a id="ember779"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;link&quot;,&quot;targetId&quot;:&quot;SeeAllRecentAlbums&quot;}"
               rel="nofollow" href="/fr/artist/a$ap-rocky/481488005#see-all/recent-albums"
               class="link section__nav__see-all-link ember-view">Tout afficher</a>
        </div>
        <div class="l-row l-row--peek">

            <a id="ember791"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;1387635013&quot;,&quot;locationType&quot;:&quot;shelfRecentAlbums&quot;,&quot;locationPosition&quot;:0}"
               href="https://itunes.apple.com/fr/album/testing/1387635013" data-test-we-lockup-id="1387635013"
               data-test-we-lockup-kind="album"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember792"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/146x0w.jpg 1x,https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/292x0w.jpg 2x,https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/438x0w.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/158x0w.jpg 1x,https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/316x0w.jpg 2x,https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/474x0w.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/200x0w.jpg 1x,https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/400x0w.jpg 2x,https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/600x0w.jpg 3x">
                    <img src="https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/66/30/00/66300027-da01-5e6c-eba2-3ada3027c404/886447076446.jpg/200x0w.jpg"
                         style="background-color: #0f0f0f;" class="we-artwork__image ember792" alt="">
                    <style>
                        .ember792, #ember792::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember792, #ember792::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember792, #ember792::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title  icon icon-after icon-explicit">
                    <div id="ember796" class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                        TESTING
                    </div>
                </h3>

                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2018</h4>
                <!----></a>


            <a id="ember798"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;1365724546&quot;,&quot;locationType&quot;:&quot;shelfRecentAlbums&quot;,&quot;locationPosition&quot;:1}"
               href="https://itunes.apple.com/fr/album/a%24ap-forever-feat-moby-single/1365724546"
               data-test-we-lockup-id="1365724546" data-test-we-lockup-kind="album"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember799"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/146x0w.jpg 1x,https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/292x0w.jpg 2x,https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/438x0w.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/158x0w.jpg 1x,https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/316x0w.jpg 2x,https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/474x0w.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/200x0w.jpg 1x,https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/400x0w.jpg 2x,https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/600x0w.jpg 3x">
                    <img src="https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/67/71/db/6771dbce-c49a-297b-02d7-c637f04fe48f/886447013847.jpg/200x0w.jpg"
                         style="background-color: #140908;" class="we-artwork__image ember799" alt="">
                    <style>
                        .ember799, #ember799::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember799, #ember799::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember799, #ember799::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title  icon icon-after icon-explicit">
                    <div id="ember803" class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                        A$AP Forever (feat. Moby) - Single
                    </div>
                </h3>

                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2018</h4>
                <!----></a>


            <a id="ember805"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;1362807556&quot;,&quot;locationType&quot;:&quot;shelfRecentAlbums&quot;,&quot;locationPosition&quot;:2}"
               href="https://itunes.apple.com/fr/album/bad-company-feat-blocboy-jb-single/1362807556"
               data-test-we-lockup-id="1362807556" data-test-we-lockup-kind="album"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember806"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/146x0w.jpg 1x,https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/292x0w.jpg 2x,https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/438x0w.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/158x0w.jpg 1x,https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/316x0w.jpg 2x,https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/474x0w.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/200x0w.jpg 1x,https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/400x0w.jpg 2x,https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/600x0w.jpg 3x">
                    <img src="https://is5-ssl.mzstatic.com/image/thumb/Music118/v4/c5/b8/ea/c5b8ea24-636c-04dd-0cb6-c3d32a2984a9/886447018835.jpg/200x0w.jpg"
                         style="background-color: #1b1b23;" class="we-artwork__image ember806" alt="">
                    <style>
                        .ember806, #ember806::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember806, #ember806::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember806, #ember806::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title  icon icon-after icon-explicit">
                    <div id="ember810" class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                        Bad Company (feat. BlocBoy JB) - Single
                    </div>
                </h3>

                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2018</h4>
                <!----></a>


            <a id="ember812"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;1348030820&quot;,&quot;locationType&quot;:&quot;shelfRecentAlbums&quot;,&quot;locationPosition&quot;:3}"
               href="https://itunes.apple.com/fr/album/cocky-feat-london-on-da-track-single/1348030820"
               data-test-we-lockup-id="1348030820" data-test-we-lockup-kind="album"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember813"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/146x0w.jpg 1x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/292x0w.jpg 2x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/438x0w.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/158x0w.jpg 1x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/316x0w.jpg 2x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/474x0w.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/200x0w.jpg 1x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/400x0w.jpg 2x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/600x0w.jpg 3x">
                    <img src="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/80/a8/50/80a8502f-d80d-2b24-abbc-8d3db5f751f5/886446960906.jpg/200x0w.jpg"
                         style="background-color: #d3d3d3;" class="we-artwork__image ember813" alt="">
                    <style>
                        .ember813, #ember813::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember813, #ember813::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember813, #ember813::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title  icon icon-after icon-explicit">
                    <div id="ember817" class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                        Cocky (feat. London On Da Track) - Single
                    </div>
                </h3>

                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2018</h4>
                <!----></a>


            <a id="ember819"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;1324158642&quot;,&quot;locationType&quot;:&quot;shelfRecentAlbums&quot;,&quot;locationPosition&quot;:4}"
               href="https://itunes.apple.com/fr/album/no-limit-feat-a%24ap-rocky-french-montana-juicy-j-belly/1324158642"
               data-test-we-lockup-id="1324158642" data-test-we-lockup-kind="album"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember820"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/146x0w.jpg 1x,https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/292x0w.jpg 2x,https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/438x0w.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/158x0w.jpg 1x,https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/316x0w.jpg 2x,https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/474x0w.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/200x0w.jpg 1x,https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/400x0w.jpg 2x,https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/600x0w.jpg 3x">
                    <img src="https://is2-ssl.mzstatic.com/image/thumb/Music118/v4/80/08/9b/80089bd2-24df-2338-2d37-5c2a854f1994/886446884677.jpg/200x0w.jpg"
                         style="background-color: #040404;" class="we-artwork__image ember820" alt="">
                    <style>
                        .ember820, #ember820::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember820, #ember820::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember820, #ember820::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title  icon icon-after icon-explicit">
                    <div id="ember824" class="we-truncate targeted-link__target we-truncate--single-line ember-view"> No
                        Limit (feat. A$AP Rocky, French Montana, Juicy J &amp; Belly) [Remix] - Single
                    </div>
                </h3>

                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2017</h4>
                <!----></a>


            <a id="ember826"
               data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;1324163097&quot;,&quot;locationType&quot;:&quot;shelfRecentAlbums&quot;,&quot;locationPosition&quot;:5}"
               href="https://itunes.apple.com/fr/album/no-limit-feat-a%24ap-rocky-french-montana-juicy-j-belly/1324163097"
               data-test-we-lockup-id="1324163097" data-test-we-lockup-kind="album"
               class="we-lockup targeted-link l-column small-2 medium-3 large-2 ember-view">
                <picture id="ember827"
                         class="we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                    <source srcset="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/146x0w.jpg 1x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/292x0w.jpg 2x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/438x0w.jpg 3x"
                            media="(min-width: 1069px)">
                    <!---->
                    <source srcset="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/158x0w.jpg 1x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/316x0w.jpg 2x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/474x0w.jpg 3x"
                            media="(min-width: 736px)">
                    <!---->
                    <source srcset="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/200x0w.jpg 1x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/400x0w.jpg 2x,https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/600x0w.jpg 3x">
                    <img src="https://is1-ssl.mzstatic.com/image/thumb/Music118/v4/75/d3/d3/75d3d36c-9a9c-30f0-20b7-cc390f5006ec/886446884684.jpg/200x0w.jpg"
                         style="background-color: #040404;" class="we-artwork__image ember827" alt="">
                    <style>
                        .ember827, #ember827::before {
                            width: 200px;
                            height: 200px;
                        }

                        @media (min-width: 736px) {
                            .ember827, #ember827::before {
                                width: 158px;
                                height: 158px;
                            }
                        }

                        @media (min-width: 1069px) {
                            .ember827, #ember827::before {
                                width: 146px;
                                height: 146px;
                            }
                        }
                    </style>
                </picture>

                <!---->
                <h3 class="we-lockup__title ">
                    <div id="ember831" class="we-truncate targeted-link__target we-truncate--single-line ember-view"> No
                        Limit (feat. A$AP Rocky, French Montana, Juicy J &amp; Belly) [Remix] - Single
                    </div>
                </h3>

                <h4 class="we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">2017</h4>
                <!----></a>


        </div>
    </section>
    <?
    exit;
} ?>


<select class="test" name="artists[]" multiple="multiple" style="width:100%"></select>
<button class="testGo">Ajouter</button>
<a href="index.php?refresh=refresh">REFRESH</a>

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
