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
$api_request = setUp($url);

$redirect = 'index.php';
switch ($api_request->route) {
	case '/':
    case '':
    case '/test':
		break;
    case '/releases':
        $redirect = 'v1/releases.php';
		break;
	case '/tests':
        $redirect = 'tests/index.php';
		break;
	default:
        $redirect = PAGE_ERROR_404;
        http_response_code(404);
		break;
}

$api_request->infos->file_path = str_replace('\\', '/', __DIR__ . "/$redirect");

if(!is_file($api_request->infos->file_path)) {
    //exit("error: can't find {$api_request->infos->file_path}");
    $redirect = PAGE_ERROR_404;
    $api_request->infos->file_path = str_replace('\\', '/', __DIR__ . "/$redirect");
    http_response_code(404);
}

//header("Content-type: application/json");
require __DIR__ . '/../vendor/autoload.php';
require $api_request->infos->file_path;