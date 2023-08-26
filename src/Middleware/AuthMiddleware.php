<?php

namespace src\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use src\AbstractElement;

class AuthMiddleware extends AbstractElement implements IMiddleware {

	public function handle(Request $request): void {
		if(!$this->app->isLogged()) {
			// Redirecting to login page
//			$request->setRewriteUrl($this->app::url('user.login'));
			$this->app::response()->redirect($this->app::url('user.login'));
		}

	}
}