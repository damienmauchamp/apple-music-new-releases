<?php

namespace src\Database;

use PDO;
use PDOException;
use PDOStatement;
use src\App;

class Manager {

	protected PDO $pdo;
	private string $host;
	private string $base;
	private string $username;
	private string $password;
	private ?int $port = null;

	public function __construct(App $app) {
		$this->host = $app->env('DB_HOST', 'localhost');
		$this->base = $app->env('DB_NAME');
		$this->username = $app->env('DB_USERNAME');
		$this->password = $app->env('DB_PWD');
		$this->port = $app->env('DB_PORT');
		$this->connect();
	}

	/**
	 * Connecting to database
	 * @throws PDOException
	 */
	private function connect() {
		$options = [
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
			\PDO::ATTR_EMULATE_PREPARES => false,
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		];

		$this->pdo = new PDO(sprintf("mysql:host={$this->host};%sdbname={$this->base}", $this->port ? "port={$this->port};" : ''),
			$this->username,
			$this->password,
			$options);
	}


	/**
	 * @param string $query
	 * @param array $params
	 * @param bool $rowCount (default : FALSE)
	 * @return bool|int
	 */
	public function exec(string $query, array $params = [], bool $rowCount = false) {
		$stmt = $this->pdo->prepare($query);
		$result = $stmt->execute($params);
		return $rowCount ? $stmt->rowCount() : $result;
	}

	public function run(string $query, array $params = []): PDOStatement {
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($params);
		return $stmt;
	}

	public function find(string $query, array $params = []) {
		return $this->run($query, $params)->fetchAll();
	}

	public function findOne(string $query, array $params = []) {
		return $this->run($query, $params)->fetch();
	}

	public function find2(string $table, array $params = []) {
		$query = $this->setQuery($table, $params);
		echo '<pre>'.print_r($query, true).'</pre>';
//		$this->parseParams($query, $params);
		return $this->find($query, $params);
	}

	public function findOne2(string $table, array $params = []) {
		$query = $this->setQuery($table, $params);
//		$this->parseParams($query, $params);
		return $this->findOne($query, $params);
	}

	private function setQuery(string $table, array &$params = []): string {
		$where = [];
		foreach(array_keys($params) as $key) {
			$where[] = sprintf('%s = :%s', $key, $key);
		}
		return sprintf('SELECT * FROM %s WHERE %s', $table, $where ? implode(' AND ', $where) : 1);
	}

	private function setUpdateQuery(string $table, array &$params = [], array $where = []): string {

		$real_params = [];

		$set = [];
		foreach($params as $key => $value) {
//			$set[] = '? = ?';
			$set[] = sprintf('%s = ?', $key);
			$real_params[] = $value;
		}

		$conditions = [];
		foreach($where as $key => $value) {
//			$conditions[] = '? = ?';
			$conditions[] = sprintf('%s = ?', $key);
			$real_params[] = $value;
		}

		$sets = implode(', ', $set);
		$params = $real_params;
		return sprintf("UPDATE %s SET {$sets} WHERE %s", $table,
			$conditions ? implode(' AND ', $conditions) : 1);
	}

	public function update(string $table, array $params, array $where): int {
		if(!$params || !$where) {
			return 0;
		}
		$query = $this->setUpdateQuery($table, $params, $where);
		return $this->exec($query, $params, true);
	}

}