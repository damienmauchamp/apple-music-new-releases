<? if (!isset($root)) {
    $root = '../';
}

$title_prefix = is_localhost() ? "[DEV] " : '';
$page_title = isset($pageTitle) ? $pageTitle : "Apple Music Update";
?>
    <title><?= $title_prefix . $page_title ?></title>
    <script src="<?= $root ?>libs/jquery/jquery-1.12.1.js"></script>
    <script src="<?= $root ?>libs/jquery/jquery.migrate-1.2.1.min.js"></script>
    <!-- <script src="<?= $root ?>libs/jquery/jquery.mobile-1.4.5.min.js"></script> -->
    <link href="<?= $root ?>libs/select2/select2.min.css" rel="stylesheet"/>
    <script src="<?= $root ?>libs/select2/select2.min.js"></script>
    <link rel="stylesheet" href="<?= $root ?>css/albums.css">
    <link rel="stylesheet" href="<?= $root ?>css/main.css">
    <link rel="stylesheet" href="<?= $root ?>css/dark.css">
    <link rel="stylesheet" href="<?= $root ?>css/fonts/sf-display.css">
    <link rel="stylesheet" href="<?= $root ?>css/fonts/sf-text.css">
<? // if ($mobile) : ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="<?= $root ?>js/main.js"></script>
    <link rel="icon" type="image/png" href="<?= $root ?>favicon.png">
    <link rel="apple-touch-icon" href="<?= $root ?>favicon.png">
<? // endif; ?>

    <script src="<?= $root ?>libs/fontawesome/fontawesome-5.11.2.js"></script>
    <link rel="stylesheet" href="<?= $root ?>libs/fontawesome/css/fontawesome-5.11.2.css">