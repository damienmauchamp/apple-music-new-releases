<?php
//date_default_timezone_set("Europe/Paris");
session_start();
require_once("functions.php");
$debug = false;

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
define("TIMESTAMP_MIDNIGHT", strtotime(date("Y-m-d 00:00:00")));
define("TIMESTAMP_6AM", strtotime(date("Y-m-d 06:00:00")));
define("TIMESTAMP_6PM", strtotime(date("Y-m-d 18:00:00")));
define("TIMESTAMP_NOW", strtotime("now"));

if(isset($argv)) {
	foreach($argv as $arg) {
		$e = explode("=", $arg);
		if(count($e) == 2)
			$_GET[$e[0]] = $e[1];
		else
			$_GET[$e[0]] = 0;
	}
}

define('DEFAULT_PATH', __DIR__);
define('LOG_FILE', DEFAULT_PATH.'/log.log');
define('DAYS', 7);

$detect = new Mobile_Detect;
$mobile = $detect->isMobile();
//$page = isset($_GET["page"]) ? $_GET["page"] : null;

// theme
$theme = "is-music-theme";
if(!empty($_COOKIE['theme']) && in_array($_COOKIE['theme'], ['light', 'dark', 'night', 'variant-dark'])) {
	$theme .= " {$_COOKIE['theme']}";
}

$daysInterval = 7;
$news = isset($_GET["refresh"]);
$scrapped = isset($_GET["scrapped"]);
$full = isset($_GET["full"]);
$delay = !empty($_GET['delay']) ? intval($_GET['delay']) : 0;
//$news = isset($_GET["refresh"]) && $_GET["refresh"] ? $_GET["refresh"] : false;
$display = $news ? "column" : "row";
$nodisplay = isset($_GET["nodisplay"]);
//echo date(DEFAULT_DATE_FORMAT_TIME);

$navTitle = "Bonjour ";
if(TIMESTAMP_MIDNIGHT <= TIMESTAMP_NOW && TIMESTAMP_NOW < TIMESTAMP_6AM)
	$navTitle = "Bonne nuit ";
else if(TIMESTAMP_6PM <= TIMESTAMP_NOW && TIMESTAMP_NOW < TIMESTAMP_MIDNIGHT)
	$navTitle = "Bonsoir ";
$navTitle .= (isset($_SESSION["prenom"]) ? $_SESSION["prenom"] : null);

// run from command line :
//  php -f index.php refresh=1 full=1

function getToken($length) {
	$token = "";
	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet .= "0123456789";
	$max = strlen($codeAlphabet) - 1;
	for($i = 0; $i < $length; $i++) {
		$token .= $codeAlphabet[cryptoRandSecure(0, $max)];
	}
	return $token;
}

function cryptoRandSecure($min, $max) {
	$range = $max - $min;
	if($range < 1) {
		return $min; // not so random...
	}
	$log = ceil(log($range, 2));
	$bytes = (int) ($log / 8) + 1; // length in bytes
	$bits = (int) $log + 1; // length in bits
	$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
	do {
		$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
		$rnd = $rnd & $filter; // discard irrelevant bits
	} while($rnd >= $range);
	return $min + $rnd;
}

function clearAuthCookie() {
	if(isset($_COOKIE["user_login"])) {
		setcookie("user_login", "");
	}
	if(isset($_COOKIE["random_password"])) {
		setcookie("random_password", "");
	}
	if(isset($_COOKIE["random_selector"])) {
		setcookie("random_selector", "");
	}
}