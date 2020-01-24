<?php
define('PAGE_FOLDER', 'pages');
define('ERRORS_FOLDER', 'errors');
define('PAGE_ERROR_404', ERRORS_FOLDER . '/404.php');

function setUp($url) {
	// parsing url
	$parsed_url = parse_url($url);

	/**
	 * @todo: prendre le nom de base du dossier contenant tout le code, car s'il on va par exemple sur {URL}/pages/, la route sera "/"
	 * exemple : {URL}/beta/pages/pages/errors/pages/pages/test = {URL}/test
	 */
	$route = preg_replace('/^.*(\/.*)/', '$1', $parsed_url['path']);

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
$page_request = setUp($url);

$redirect = 'index.php';
$page_name = "accueil";
switch ($page_request->route) {
	case '/':
    case '':
        $redirect = 'index.php';
		$page_name = "accueil";
		break;
    case '/test':
        $redirect = 'test.php';
		$page_name = "test";
		break;
	default:
        $redirect = PAGE_ERROR_404;
        http_response_code(404);
		break;
}

$page_request->infos->file_path = str_replace('\\', '/', __DIR__ . "/".PAGE_FOLDER."/$redirect");
if(!is_file($page_request->infos->file_path)) {
    $redirect = PAGE_ERROR_404;
    $page_request->infos->file_path = str_replace('\\', '/', __DIR__ . "/$redirect");
    http_response_code(404);
}

//header("Content-type: application/json");
require __DIR__ . '/../vendor/autoload.php';

// define variables
echo "REDIRECT {$page_name}:{$redirect}\n";

// redirect
require $page_request->infos->file_path;