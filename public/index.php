<?php

use Pecee\SimpleRouter\SimpleRouter;
use src\App;

require_once '../load.php';

$app = App::get();

$app->loadRoutes('auth');
$app->loadRoutes('users');
$app->loadRoutes('', 'errors');
$app->loadRoutes('api');

SimpleRouter::setDefaultNamespace('\src\Controllers');

// Start the routing
SimpleRouter::start();