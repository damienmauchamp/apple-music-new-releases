<?php

//
use Pecee\SimpleRouter\SimpleRouter;
use src\Controllers\AuthController;
use src\Middleware\LoggedMiddleware;

SimpleRouter::group(['middleware' => LoggedMiddleware::class], function () {
	SimpleRouter::get('/login', [AuthController::class, 'login'])->name('user.login');
});