<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . "/start.php";

use AppleMusic\Artist as Artist;
use AppleMusic\DB as db;

$artists = isset($_GET["artists"]) ? $_GET["artists"] : null;
$userToken = $_GET['userToken'] ?? $_POST['userToken'] ?? null;
$userId = $_SESSION['id_user'] ?? null;

//
if ($userId === null && $userToken !== null) {
	//
	$userInfos = (new db)->getUserFromUserToken($userToken);
	if (!$userInfos) {
		$userToken = null;
	}
}


// Unauthorized
if (!($userToken ?? $userId ?? false)) {
    http_response_code(404);
    exit();
}

$results = [
    'artists' => [],
    'infos' => [
        'userToken' => $userToken,
        'userId' => $userId,
    ],
];
foreach ($artists as $id) {
    $artist = new Artist($id);

    //
    if (!$artist->fetchArtistInfo()) {
        $results['artists'][] = [
            'id' => $id,
            'added' => false,
            'message' => "Couldn't fetch artist with ID {$id}",
        ];
        continue;
    }

    $added = $artist->addArtist($userId);
    $results['artists'][] = [
        'id' => $id,
        'added' => $added,
        'message' => !$added ? 'Something went wrong' : '',
    ];
}

echo json_encode($results);