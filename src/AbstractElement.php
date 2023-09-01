<?php

namespace src;

class AbstractElement {

	protected App $app;

	public function __construct() {
		$this->app = App::get();
	}

	protected function getApp(): App {
		return $this->app;
	}

}