<?php

use Pecee\SimpleRouter\SimpleRouter;
use src\Middleware\AuthMiddleware;

SimpleRouter::group(['middleware' => AuthMiddleware::class], function () {
	SimpleRouter::get('/', function () {
		return 'user.homepage';
	})->name('user.homepage');

	SimpleRouter::get('/user/profile', function () {
		return 'user.profile';
	})->name('user.profile');
});