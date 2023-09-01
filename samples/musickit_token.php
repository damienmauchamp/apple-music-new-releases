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

$token_valid = $user->isValidToken();
?>

<html lang="en">
	<head>
		<title>Apple MusicKit Token Generation</title>
	</head>
	<body>
		<div>Hi <?= $user->getFirstName() ?>!</div>
		<div>Token valid : <?= $token_valid ?></div>
		<div>Token creation : <?= $user->getTokenCreationDateToString() ?></div>
		<!--		<div>Token expiracy : --><?php //= $user->getTokenExpirationDateToString() ?><!--</div>-->
		<button id="login-btn"><?= $token_valid ? 'Already logged, refresh token ?' : 'Login to Apple Music' ?></button>
		<script src="https://js-cdn.music.apple.com/musickit/v1/musickit.js"></script>
		<!--		<script src="/musickit/v1/musickit.js"></script>-->
		<script src="music_token.js"></script>
	</body>
</html>
