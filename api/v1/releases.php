<?php
// exec

$start_date = array_key_exists('start_date', $_GET) ? $_GET['start_date'] : date('Y-m-d 00:00:00');
try {
	$start_date = new \DateTime($start_date);
} catch(Exception $e) {
	exit(json_encode(array(
		'status' => 500,
		'data' => $res ?: [],
		'error' => $e
	)));
}

$db = new AppleMusic\DB();
$sql = "
	SELECT *
	FROM albums a
	WHERE a.added > '".$start_date->format('Y-m-d H:i:s')."'
	ORDER BY a.added ASC";
$res = $db->selectPerso($sql);

$status_code = $res ? 200 : 204;

foreach ($res as $i => $item) {
	foreach ($item as $key => $value) {
	    if (is_int($key)) {
	        unset($res[$i][$key]);
	    }
	}
}

echo json_encode(array(
	'status' => $status_code,
	'data' => $res ?: [],
	'params' => $_GET
));

//$start_date->format('Y-m-d H:i:s');
exit();

$test = "Test::getUserAlbums();";
$x = eval($test);