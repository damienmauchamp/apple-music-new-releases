<?php

namespace src\Database;

use PDO;
use PDOException;

trait DBTrait {

	private Manager $manager;

	protected function manager(): Manager {
//		if(!$this->manager) {
//			$this->manager = new Manager();
//		}
		return $this->manager;
	}

}