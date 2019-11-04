<?php
define('API_NAME', 'api');
define('API_PATH', '/'.API_NAME.'/');

define('PAGE_ERROR_404', 'errors/404.php');

function setUp($url) {
	// parsing url
	$parsed_url = parse_url($url);

	$route = preg_replace('/^.*\/'.API_NAME.'(\/.*)/', '$1', $parsed_url['path']);

	// params
	$params = [];
	$parsed_params = !empty($parsed_url['query']) ? parse_str(preg_replace('/\??(.*)/', '$1', $parsed_url['query']), $params) : null;

	// return
	return (object) array(
		'route' => $route,
        'query' => $params,
        'params' => [],
        'infos' => (object) [
            'route' => $route
        ]
	);
}

$url = $_SERVER['REQUEST_URI'];
$request = setUp($url);

$redirect = 'index.php';
switch ($request->route) {
	case '/':
    case '':
    case '/test':
		break;
	case '/tests':
        $redirect = 'tests/index.php';
		break;
	default:
        $redirect = PAGE_ERROR_404;
        http_response_code(404);
		break;
}

$request->infos->file_path = str_replace('\\', '/', __DIR__ . "/$redirect");

if(!is_file($request->infos->file_path)) {
    //exit("error: can't find {$request->infos->file_path}");
    $redirect = PAGE_ERROR_404;
    $request->infos->file_path = str_replace('\\', '/', __DIR__ . "/$redirect");
    http_response_code(404);
}

require $request->infos->file_path;