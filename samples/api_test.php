<?php

use API\AbstractAPI;
use API\AppleMusic;
use API\iTunesAPI;
use API\iTunesScrappedAPI;
use API\MusicKit;
use GuzzleHttp\Exception\GuzzleException;
use src\App;

require '../load.php';

// Getting app
$app = App::get();
if(!$app->isLogged()) {
	http_response_code(401);
	exit('Not logged');
}

switch($_GET['type'] ?? null) {
	case 'itunes':
		$api = new iTunesAPI();
		break;
	case 'itunes-scrapped':
		$api = new iTunesScrappedAPI();
		break;
	case 'apple-music':
		$api = new AppleMusic();
		break;
	case 'musickit':
		$api = new MusicKit();
		break;
	default:
		$api = new AbstractAPI();
		break;

}
try {
	$response = $api->test();
	dump([
//		'$api' => $api,
		'$response' => $response,
		'data' => $response->getData(),
		'status' => $response->getStatusCode(),
	]);
} catch(GuzzleException $e) {
	dump([
//		'$api' => $api,
		'$e' => $e,
	]);
}