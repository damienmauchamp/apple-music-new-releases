<?php

use Pecee\SimpleRouter\SimpleRouter;
use src\App;

require_once '../load.php';

$app = App::get();

$app->loadRoutes('auth');
$app->loadRoutes('users');
$app->loadRoutes('api');
$app->loadRoutes('tests');
$app->loadRoutes('', 'errors');

SimpleRouter::setDefaultNamespace('\src\Controllers');

// Start the routing
SimpleRouter::start();