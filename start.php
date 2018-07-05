<?php
//date_default_timezone_set("Europe/Paris");
require_once("functions.php");

define("DEFAULT_DATE_FORMAT", "Y-m-d");
define("DEFAULT_DATE_FORMAT_NO_SECS", "Y-m-d H:i");
define("DEFAULT_DATE_FORMAT_TIME", "Y-m-d H:i:s");
define("WEEKDAYS_NAMES", serialize(array(
    1 => "lundi",
    2 => "mardi",
    3 => "mercredi",
    4 => "jeudi",
    5 => "vendredi",
    6 => "samedi",
    7 => "dimanche"
)));
define("MONTHS_NAMES", serialize(array(
    1 => "janvier",
    2 => "février",
    3 => "mars",
    4 => "avril",
    5 => "mai",
    6 => "juin",
    7 => "juillet",
    8 => "août",
    9 => "septembre",
    10 => "octobre",
    11 => "novembre",
    12 => "décembre"
)));
define("MONTHS_NAMES_SHORT", serialize(array(
    1 => "janv.",
    2 => "févr.",
    3 => "mars",
    4 => "avr.",
    5 => "mai",
    6 => "juin",
    7 => "juil.",
    8 => "août",
    9 => "sept.",
    10 => "oct.",
    11 => "nov.",
    12 => "déc."
)));

$detect = new Mobile_Detect;
$mobile = $detect->isMobile();
$page = isset($_GET["page"]) ? $_GET["page"] : null;
$theme = "is-music-theme";
$news = isset($_GET["refresh"]) && $_GET["refresh"];
$display = $news ? "column" : "row";
$idUser = 1;
//echo date(DEFAULT_DATE_FORMAT_TIME);
