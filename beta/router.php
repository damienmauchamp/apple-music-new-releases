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
		'file_path' => null,
        'query' => $params,
        'params' => $_POST,
	);
}

$url = $_SERVER['REQUEST_URI'];
$page = (object) [
	'file' => 'index.php',
	'name' => 'home',
	'title' => '',
	'request' => setUp($url),
	'content' => [
		'sections' => [
			[
				'type' => "albums",
				'title' => "Sorties de la semaine",
				'display' => [
					'format' => 'grid',
					'bordered' => true
				],
				'options' => [
					'max_days' => 7,
					'explicit_only' => true,
					'preorders' => false,
				],
				'code' => "week_releases",
			],
			[], // pré-commandes (à venir)
			[] // liste chanson (Nouveaux titres)
		]
	]
];
switch ($page->request->route) {
	case '/':
    case '':
    	//$page->file = 'index.php';
    	//$page->name = 'home';
    	//$page->title = '';
		break;
    case '/test':
    	$page->file = 'test.php';
    	$page->name = 'test';
    	$page->title = 'Test';
		break;
	case '/albums':
    	$page->file = 'XXXXXX.php';
    	$page->name = 'XXXXXX';
    	$page->title = 'XXXXX';
    	// liste des albums par artists (>30j)
	case '/songs':
    	$page->file = 'songs.php';
    	$page->name = 'songs';
    	$page->title = 'Chansons';
    	// liste des chansons (>60j)
	case '/artists':
	case '/artistes':
    	$page->file = 'artists.php';
    	$page->name = 'artists';
    	$page->title = 'Mes artistes';
    	// liste des artistes
	default:
        $page->file = PAGE_ERROR_404;
    	$page->name = 'error';
    	$page->title = 'Erreur';
        http_response_code(404);
		break;
}

$page->request->file_path = str_replace('\\', '/', __DIR__ . "/".PAGE_FOLDER."/$page->file");
if(!is_file($page->request->file_path)) {
    $page->file = PAGE_ERROR_404;
    $page->request->file_path = str_replace('\\', '/', __DIR__ . "/$page->file");
    http_response_code(404);
}

//header("Content-type: application/json");
require __DIR__ . '/../vendor/autoload.php';

// common
echo "<title>[BETA] AMU v2" . ($page->title ? " | {$page->title}" : "") . "</title>";

// define variables
echo "REDIRECT {$page->name}:{$page->file}\n\n";

// redirect
require $page->request->file_path;