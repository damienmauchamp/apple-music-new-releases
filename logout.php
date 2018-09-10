<?
require __DIR__ . '/vendor/autoload.php';
require_once("start.php");

global $idUser;
$idUser = -1;

// On détruit les variables de notre session
session_unset ();

// On détruit notre session
session_destroy ();

// On redirige le visiteur vers la page d'accueil
header ('location: login.php');
