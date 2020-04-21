<?
$menu = isConnected() ? array(
    array("Accueil", "index.php", "normal"),
    array("Mes artistes", "artists.php", "normal"),
    array("MAJ", "index.php?refresh", "compact"),
    // array("Hard MAJ", "index.php?refresh&scrapped=1", "compact"),
    array("Déconnexion", "logout.php", "normal")
) : array(array("Accueil", "index.php", "normal"));

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

                <div id="nav-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="mobile-menu" style="display:none">
        <ul>
            <? foreach ($menu as $item) {
                $name = $item[0];
                $link = $item[1];
                $type = $item[2];
                ?>
                <li class="<?= $name === "MAJ" ? "maj" : null ?>">
                    <a href="<?= $option[$type]["a"]["href"] . $link ?>">
                        <?= $name ?>
                    </a>
                </li>
            <? } ?>
        </ul>
    </div>
</nav>