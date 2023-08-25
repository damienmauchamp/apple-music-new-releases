<?php

use Entity\Users\User;
use src\App;

//echo '<pre>'.print_r([
//		'$_GET' => $_GET,
//		'$_POST' => $_POST,
//		'get_defined_vars' => get_defined_vars(),
//	], true).'</pre>';
//exit();

require '../load.php';


// Getting app
$app = App::get();
if(!$app->isLogged()) {
	http_response_code(401);
	exit('Not logged');
}

$user = $app->getUser();

$body = json_decode(file_get_contents('php://input'), true);
$token = $body ? ($body['token'] ?? '') : '';
if($token) {
	$result = [
		'error' => false,
		'message' => 'Token saved.',
	];
	$generated = $_POST['generated'] ?? 'now';
	try {
		$user->setMusicToken($token, null, $generated)
			->updateToken();
	} catch(Exception $e) {
		$result['error'] = true;
		$result['message'] = $e->getMessage();
	}
	exit(json_encode($result));
}
?>

<html lang="en">
	<head>
		<title>Apple MusicKit Token Generation</title>
	</head>
	<body>
		Hi <?= $user->getFirstName() ?>!
		<button id="login-btn">Login to Apple Music</button>
		<script src="https://js-cdn.music.apple.com/musickit/v1/musickit.js"></script>
		<script src="music_token.js"></script>
	</body>
</html>
