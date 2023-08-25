<?php

namespace src;

use Dotenv\Dotenv;
use Entity\Users\User;
use src\Database\Manager;
use src\Token\JWT;

class App {

	private const BASE_DIR = __DIR__.'/..';

	private array $env;
	private ?User $user = null;
	protected Manager $manager;

	public function __construct() {

		// .env
		$dotenv = Dotenv::createImmutable(self::BASE_DIR);
		$dotenv->safeLoad();
		$this->env = $_ENV ?? [];

//		// User
//		$this->user = User::current();

		// Manager
		$this->manager = new Manager($this);
	}

	public static function load(): self {
		return new self();
	}

	public static function get(): self {
		return get_defined_vars()['app'] ?? self::load();
	}

	public function getUser(): ?User {
		if(!$this->user) {
			$this->user = User::current();
		}
		return $this->user;
	}


	public function getUserId(): ?int {
//		return $this->getUser()?->getId(); // PHP 8
		return $this->getUser() ? $this->getUser()->getId() : null;
	}

	public function isLogged(): bool {
		return (bool) User::current();
//		return (bool) $this->user;
	}

	public function env(string $parameter, $default = null) {
		return $this->env[$parameter] ?? $default;
	}

	public function manager(): Manager {
		return $this->manager;
	}

	private function getAppleAuthKeyPath(): string {
		return sprintf('%s/server/keys/%s', self::BASE_DIR, $this->env('APPLE_AUTH_KEY'));
	}

	public function getAppleAuthKey(): string {
		$path = $this->getAppleAuthKeyPath();
		if(!is_file($path)) {
			return '';
		}
		return file_get_contents($path);
	}

	public function getDeveloperToken(int $expiracy = 3600): string {
		$private_key = $this->getAppleAuthKey();
		$team_id = $this->env('APPLE_TEAM_ID');
		$key_id = $this->env('APPLE_KEY_ID');
		$expiracy = $_POST['expiracy'] ?? 3600;
		return JWT::getToken($private_key, $key_id, $team_id, null, (int) $expiracy);
	}

}