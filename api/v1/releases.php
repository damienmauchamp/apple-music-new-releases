<?php
// exec
$now = new DateTime();

$start_date = array_key_exists('start_date', $_GET) ? $_GET['start_date'] : date('Y-m-d 00:00:00');
$min_release_date = array_key_exists('min_release_date', $_GET) ? $_GET['min_release_date'] : date($now->sub(new DateInterval('P1M'))->format('Y-m-d 00:00:00'));
$only_explicit = array_key_exists('only_explicit', $_GET) ? boolval($_GET['only_explicit']) : true;

// start_date
try {
	$start_date = new \DateTime($start_date);
} catch(Exception $e) {
    http_response_code(400);
	exit(json_encode(array(
		'status' => $status_code,
		'data' => ['error' => "'{$start_date}' is not a valid date."]
	)));
	//exit($e);
}

// start_date
try {
	$min_release_date = new \DateTime($min_release_date);
} catch(Exception $e) {
    http_response_code(400);
	exit(json_encode(array(
		'status' => $status_code,
		'data' => ['error' => "'{$min_release_date}' is not a valid date."]
	)));
	//exit($e);
}

$db = new AppleMusic\DB();
$sql = "
	SELECT *
	FROM albums a
	WHERE a.added > '".$start_date->format('Y-m-d H:i:s')."' AND a.date >= '".$min_release_date->format('Y-m-d H:i:s')."'
	ORDER BY a.added ASC, a.explicit DESC";
$res = $db->selectPerso($sql);

$status_code = $res ? 200 : 204;

$return = array();

foreach ($res as $i => $item) {
	foreach ($item as $key => $value) {
	    if (is_int($key)) {
	        unset($res[$i][$key]);
	    }
	}
	$res[$i]['id'] = intval($res[$i]['id']);
	$res[$i]['explicit'] = boolval($res[$i]['explicit']);
	$res[$i]['link'] = "https://music.apple.com/fr/album/" . preg_replace('/-{2,}/', '-', trim(preg_replace('/[^\w-]/', '-', strtolower($item["name"])), "-")) . "/" . $item["id"];

	if ($only_explicit) {
		// find duplicates
		$indexes = array_keys(array_filter($res, function($element) use($item){ return $element['name'] === $item['name'] && $element['artistName'] === $item['artistName'];}))[0];
		$return[] = $res[$indexes];
	}
}

$data = json_encode($only_explicit ? (array_unique($return, SORT_REGULAR) ?: []) : ($res ?: []));
exit($data);
http_response_code($status_code);
exit(json_encode(array(
	'status' => $status_code,
	'data' => $only_explicit ? (array_unique($return, SORT_REGULAR) ?: []) : ($res ?: [])
)));

//$test = "Test::getUserAlbums();";
//$x = eval($test);