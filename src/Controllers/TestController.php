<?php

namespace src\Controllers;

use src\AbstractElement;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TestController extends AbstractElement {

	public function twig() {

//		$path = './templates';
		$path = '../templates';
		$file = 'app.html.twig';

		try {
			$loader = new FilesystemLoader($path);
		} catch(\Twig\Error\LoaderError $e) {
			dd([
				'exception' => $e,
				'erreur' => $e->getMessage(),
				'type' => 'LoaderError',
			]);
		}

		// creating environment
		$twig = new Environment($loader);

		// filters
		$filters = [];
		foreach($filters as $filter) {
			$twig->addFilter($filter);
		}

		// rendering
		try {
			echo "File $file : <br/>";
			return $twig->render($file, [
				'name' => 'Fabien',
			]);

		} catch(LoaderError $e) {
			dump([
				'exception' => $e,
				'erreur' => $e->getMessage(),
				'type' => 'LoaderError',
			]);
		} catch(RuntimeError $e) {
			dump([
				'exception' => $e,
				'erreur' => $e->getMessage(),
				'type' => 'RuntimeError',
			]);
		} catch(SyntaxError $e) {
			dump([
				'exception' => $e,
				'erreur' => $e->getMessage(),
				'type' => 'SyntaxError',
			]);
		}
	}

}