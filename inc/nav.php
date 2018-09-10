<nav id="localnav" class="we-localnav localnav css-sticky" role="navigation">
    <div class="localnav-wrapper">
        <div class="localnav-background we-localnav__background"></div>
        <div class="localnav-content">
            <h2 id="ember635"
                class="localnav-title we-truncate we-truncate--single-line ember-view"><?= isset($navTitle) ? $navTitle : "Apple Music" ?>
            </h2>
            <div class="localnav-menu we-localnav__menu we-localnav__menu--music">
                <div class="localnav-actions we-localnav__actions">
                    <div class="localnav-action localnav-action-button we-localnav__action we-localnav__action--login">
                        <a id="apple-music-authorize"
                           class="localnav-button localnav-button--sign-in we-button we-button--compact we-button-flat we-button-flat--plain"
                           href="<?= $root ?>index.php">
                            Accueil
                        </a>
                    </div>
                    <div class="localnav-action localnav-action-button we-localnav__action we-localnav__action--login">
                        <a id="apple-music-authorize"
                           class="localnav-button localnav-button--sign-in we-button we-button--compact we-button-flat we-button-flat--plain"
                           href="#">
                            Mes artistes
                        </a>
                    </div>
                    <div class="localnav-action localnav-action-button we-localnav__action">
                        <a class="localnav-button we-button we-button--compact we-button--applemusic"
                           href="<?= $root ?>index.php?refresh">
                            MAJ
                        </a>
                    </div>
                    <div class="localnav-action localnav-action-button we-localnav__action we-localnav__action--login">
                        <a id="apple-music-authorize"
                           class="localnav-button localnav-button--sign-in we-button we-button--compact we-button-flat we-button-flat--plain"
                           href="<?= $root ?>logout.php">
                            Déconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>