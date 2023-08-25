<?php

use src\App;

require '../load.php';

// Getting app
$app = App::get();

$expiracy = $_GET['expiracy'] ?? $_POST['expiracy'] ?? 3600; // 15552000

@header('Content-type: application/json');
exit(json_encode([
	'token' => $app->getDeveloperToken($expiracy),
	'expiracy' => (new DateTime())->add(new DateInterval("PT{$expiracy}S"))->format('Y-m-d H:i:s.u'),
	'test' => new DateInterval("PT{$expiracy}S"),
]));