<?php

namespace src;

use DomainException;
use Dotenv\Dotenv;
use Entity\Users\User;
use Exception;
use src\Database\Manager;
use src\Router\RouterTrait;
use src\Token\JWT;

class App {
	use RouterTrait;

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

	public function getUserToken(): string {
//		return $this->getUser()?->getMusicKitToken(); // PHP 8
		return $this->getUser() ? $this->getUser()->getMusicKitToken() : '';
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
		return sprintf('%s/server/keys/%s', self::BASE_DIR, $this->env('APPLE_AUTH_KEY_FILE'));
	}

	public function getAppleAuthKey(): string {

		if($this->env('APPLE_AUTH_KEY')) {
			return $this->env('APPLE_AUTH_KEY');
		}

		$path = $this->getAppleAuthKeyPath();
		if(!is_file($path)) {
			return '';
		}
		return file_get_contents($path);
	}

	/**
	 * @throws Exception
	 */
	public function getDeveloperToken(int $expiracy = 3600): string {
		$private_key = $this->getAppleAuthKey();
		$team_id = $this->env('APPLE_TEAM_ID');
		$key_id = $this->env('APPLE_KEY_ID');
		$expiracy = $_POST['expiracy'] ?? 3600;

		if(!$private_key) {
			throw new Exception('Unable to generate developer token : no APPLE_AUTH_KEY_FILE or APPLE_AUTH_KEY found');
		}
		if(!$team_id) {
			throw new Exception('Unable to generate developer token : no APPLE_TEAM_ID found');
		}
		if(!$key_id) {
			throw new Exception('Unable to generate developer token : no APPLE_KEY_ID found');
		}

		try {
			return JWT::getToken($private_key, $key_id, $team_id, null, (int) $expiracy);
		} catch(DomainException $exception) {
			throw new Exception(sprintf('Unable to generate developer token : %s', $exception->getMessage()));
		}
	}

	// region Router
	public function loadRoutes(string $dir = '',
							   string $file = '*'): void {
		if($dir) {
			$routes_path = sprintf(self::BASE_DIR.'/server/routes/%s/%s.php', $dir, $file);
			$routes = glob($routes_path);
		}
		else {
			$routes = array_merge(
				glob(sprintf(self::BASE_DIR.'/server/routes/%s.php', $file)),
				glob(sprintf(self::BASE_DIR.'/server/routes/*/%s.php', $file))
			);
		}

		foreach($routes as $route) {
			if(preg_match('/disabled/', $route)) {
				continue;
			}
			require_once $route;
		}
	}
	// endregion Router

}