<?php

use Entity\Users\User;
use src\App;

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
		$user->setMusicKitToken($token, null, $generated)
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
		<div>Token valid : <?= $user->isValidToken() ?></div>
		<div>Token creation : <?= $user->getTokenCreationDateToString() ?></div>
		<!--		<div>Token expiracy : --><?php //= $user->getTokenExpirationDateToString() ?><!--</div>-->
		<script src="https://js-cdn.music.apple.com/musickit/v1/musickit.js"></script>
		<script src="music_token.js"></script>
	</body>
</html>
