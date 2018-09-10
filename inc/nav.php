<?
$menu = array(
    array("Accueil", "index.php", "normal"),
    array("Mes artistes", "artists.php", "normal"),
    array("MAJ", "index.php?refresh", "compact"),
    array("Déconnexion", "logout.php", "normal")
);

$option = array(
    "normal" => array(
        "div" => array(
            "class" => "localnav-action localnav-action-button we-localnav__action we-localnav__action--login",
        ),
        "a" => array(
            "class" => "localnav-button localnav-button--sign-in we-button we-button--compact we-button-flat we-button-flat--plain",
            "href" => $root
        )
    ),
    "compact" => array(
        "div" => array(
            "class" => "localnav-action localnav-action-button we-localnav__action",
        ),
        "a" => array(
            "class" => "localnav-button we-button we-button--compact we-button--applemusic",
            "href" => $root
        )
    )
)

?>

<nav id="localnav" class="we-localnav localnav css-sticky" role="navigation">
    <div class="localnav-wrapper">
        <div class="localnav-background we-localnav__background"></div>
        <div class="localnav-content">
            <h2 id="ember635"
                class="localnav-title we-truncate we-truncate--single-line ember-view"><?= isset($navTitle) ? $navTitle : "Apple Music" ?>
            </h2>
            <div class="localnav-menu we-localnav__menu we-localnav__menu--music">
                <div class="localnav-actions we-localnav__actions">

                    <? foreach ($menu as $item) {
                        $name = $item[0];
                        $link = $item[1];
                        $type = $item[2];
                        ?>
                        <div class="<?= $option[$type]["div"]["class"] ?>">
                            <a class="<?= $option[$type]["a"]["class"] ?>"
                               href="<?= $option[$type]["a"]["href"] . $link ?>">
                                <?= $name ?>
                            </a>
                        </div>
                    <? } ?>

                </div>

                <div id="nav-icon" class="localnav-actions we-localnav__actions">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function () {
        $('#nav-icon').click(function () {
            $(this).toggleClass('open');
            $('#mobile-menu').toggle();
            $('#mobile-menu').toggleClass('menu-open');
        });
    });
</script>

<div class="" id="mobile-menu" style="display: none;">
    <ul>
        <? foreach ($menu as $item) {
            $name = $item[0];
            $link = $item[1];
            $type = $item[2];
            ?>
            <li>
                <a class=""
                   href="<?= $option[$type]["a"]["href"] . $link ?>">
                    <?= $name ?>
                </a>
            </li>
        <? } ?>
    </ul>
</div>

<style>
    #nav-icon {
        width: 32px;
        height: 24px;
        /*position: absolute;*/
        /*right: 20px;*/
        /*top: -38px;*/
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        -webkit-transition: .5s ease-in-out;
        -moz-transition: .5s ease-in-out;
        -o-transition: .5s ease-in-out;
        transition: .5s ease-in-out;
        cursor: pointer;
    }

    #nav-icon span {
        display: block;
        position: absolute;
        height: 3px;
        width: 100%;
        background: #000000;
        border-radius: 9px;
        opacity: 1;
        left: 0;
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        -webkit-transition: .15s ease-in-out;
        -moz-transition: .15s ease-in-out;
        -o-transition: .15s ease-in-out;
        transition: .15s ease-in-out;
    }

    #nav-icon span:nth-child(1) {
        top: 0px;
    }

    #nav-icon span:nth-child(2), #nav-icon span:nth-child(3) {
        top: 10px;
    }

    #nav-icon span:nth-child(4) {
        top: 20px;
    }

    #nav-icon.open span:nth-child(1) {
        top: 18px;
        width: 0%;
        left: 50%;
        height: 2px;
    }

    #nav-icon.open span:nth-child(2) {
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        transform: rotate(45deg);
        height: 2px;
    }

    #nav-icon.open span:nth-child(3) {
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        transform: rotate(-45deg);
        height: 2px;
    }

    #nav-icon.open span:nth-child(4) {
        top: 18px;
        width: 0%;
        left: 50%;
        height: 2px;
    }
</style>
<script>
    $(document).ready(function () {
        $('#nav-icon').click(function () {
            $(this).toggleClass('open');
            $('#mobile-menu').toggle();
            $('#mobile-menu').toggleClass('menu-open');
        });
    });
</script>