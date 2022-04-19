<?php

// Pamameters
try {
	$filter_date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
	if (!$filter_date) {
		$filter_date = date('Y-m-d');
	}
	$date = new DateTime($filter_date);
} catch (Exception $e) {
//	$date = new DateTime();
	http_response_code(400);
	exit(json_encode([
		'status' => 400,
		'data' => ['error' => "'{$filter_date}' is not a valid date."],
	]));
}
$date->setTime(0, 0, 0);

// Query the database
$db = new AppleMusic\DB();
$sql = "
	SELECT *
	FROM albums a
	WHERE a.date LIKE '{$date->format('Y-m-d')}%'
	ORDER BY a.added, a.explicit DESC";
$res = $db->selectPerso($sql);

// Filter
$return = album_filters($res);
$data = array_values(array_unique($return, SORT_REGULAR) ?: []);
$count = count($data);
$status_code = 200;//$data ? 200 : 204;

// Return
http_response_code($status_code);
echo json_encode([
	'status' => $status_code,
	'count' => $count,
	'data' => $data,
]);
exit();
