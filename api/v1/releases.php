<?php


class Test
{
	public function getUserAlbums() {
		return "hey";
	}
}

$routes = [
	'/releases' => "Test:getUserAlbums"
];

exit();
echo json_encode($api_request);

$test = "Test::getUserAlbums();";
$x = eval($test);

$db = new AppleMusic\DB();