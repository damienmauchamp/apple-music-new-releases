<?php
require __DIR__ . '/vendor/autoload.php';
require_once("start.php");

$_SESSION['id_user'] = 0;

// On détruit les variables de notre session
session_unset ();

// On détruit notre session
session_destroy ();

// Suppression des cookies
clearAuthCookie();

// On redirige le visiteur vers la page d'accueil
header ('location: login.php');
?>