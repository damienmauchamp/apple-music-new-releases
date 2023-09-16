<?php

use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter;
use src\App;

//SimpleRouter::get('/not-found', 'PageController@notFound');
SimpleRouter::get('/not-found', function () {
	http_response_code(404);
	return '404 not-found';
})->name('error.404');

//SimpleRouter::get('/forbidden', 'PageController@notFound');
SimpleRouter::get('/forbidden', function () {
	http_response_code(403);
	return '403 forbidden';
})->name('error.403');

SimpleRouter::error(function (Request $request, \Exception $exception) {
	switch($exception->getCode()) {
		// Page not found
		case 404:
			return $request->setRewriteUrl(App::url('error.404'));
		// Forbidden
		case 403:
			return $request->setRewriteUrl(App::url('error.403'));
	}

	App::redirect('/');
	return $request->setRewriteUrl(App::url('user.homepage'));
});
