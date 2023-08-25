<?php

use Entity\Users\User;
use src\App;

require '../load.php';

// Getting app
$app = App::get();
if(!$app->isLogged()) {
	exit('Not logged');
}

$user = $app->getUser();
//dump($app, [
//	'id' => $app->getUserId(),
//	'User' => $user,
//	'expired' => $user->musicTokenExpired(),
//]);

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
