<?php
// exec
$now = new DateTime();

$start_date = array_key_exists('start_date', $_GET) ? $_GET['start_date'] : date('Y-m-d 00:00:00');
$min_release_date = array_key_exists('min_release_date', $_GET) ? $_GET['min_release_date'] : date($now->sub(new DateInterval('P1M'))->format('Y-m-d 00:00:00'));
$only_explicit = array_key_exists('only_explicit', $_GET) ? boolval($_GET['only_explicit']) : true;

// start_date
$status_code = 400;
try {
	$start_date = new \DateTime($start_date);
} catch(Exception $e) {
	http_response_code($status_code);
	exit(json_encode([
		'status' => $status_code,
		'data' => ['error' => "'{$start_date}' is not a valid date."],
	]));
	//exit($e);
}

// start_date
try {
	$min_release_date = new \DateTime($min_release_date);
} catch(Exception $e) {
	http_response_code($status_code);
	exit(json_encode([
		'status' => $status_code,
		'data' => ['error' => "'{$min_release_date}' is not a valid date."],
	]));
	//exit($e);
}

$db = new AppleMusic\DB();
$sql = "
	SELECT *
	FROM albums a
	WHERE a.added > '".$start_date->format('Y-m-d H:i:s')."'
		AND a.date IS NOT NULL
		AND a.date >= '".$min_release_date->format('Y-m-d H:i:s')."'
	ORDER BY a.added ASC, a.explicit DESC";
$res = $db->selectPerso($sql);
// $res[] = ['name' => 'test', 'artistName' => 'test', 'id' => 1, 'explicit' => true];

$return = album_filters($res, $only_explicit);
$status_code = 200;// $return ? 200 : 204;

http_response_code($status_code);
echo json_encode([
	'status' => $status_code,
	'data' => array_values($only_explicit ? (array_unique($return, SORT_REGULAR) ?: []) : ($res ?: []))
]);
exit();

//$test = "Test::getUserAlbums();";
//$x = eval($test);
