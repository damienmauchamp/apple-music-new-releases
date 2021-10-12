<?php

header("Content-type: application/json");

http_response_code(404);
echo json_encode($api_request ?? [
	'status' => 404,
]);
exit();

$routes = [
	'/releases' => "Test:getUserAlbums"
];

class Test
{
	public function getUserAlbums($params = []) {
		return "Params: " . print_r($params, 1);
	}
}

$class_method = $routes[$api_request->infos->route];
$cm_array = explode(':', $class_method);

// instanciation
eval("\$abc = new $cm_array[0];");

// exec
eval("echo \$abc->$cm_array[1](\$api_request->params);");

echo json_encode($api_request);