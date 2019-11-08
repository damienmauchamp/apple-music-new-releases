<?php
// exec

$start_date = array_key_exists('start_date', $_GET) ? $_GET['start_date'] : date('Y-m-d 00:00:00');
$only_explicit = array_key_exists('only_explicit', $_GET) ? boolval($_GET['only_explicit']) : true;
try {
	$start_date = new \DateTime($start_date);
} catch(Exception $e) {
    http_response_code(500);
	exit($e);
}

$db = new AppleMusic\DB();
$sql = "
	SELECT *
	FROM albums a
	WHERE a.added > '".$start_date->format('Y-m-d H:i:s')."'
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

	if ($only_explicit) {
		// find duplicates
		$index = array_keys(array_filter($array, function($element) use($item){ return $element['name'] === $item['name'] && $element['artistName'] === $item['artistName'];}))[0];
		$return[] = $res[$index];
	}
}

echo json_encode(array(
	'status' => $status_code,
	'data' => $only_explicit ? ($return ?: []) : ($res ?: []),
	'res' => $res,
	'ret' => $return
));

//$start_date->format('Y-m-d H:i:s');
exit();

$test = "Test::getUserAlbums();";
$x = eval($test);