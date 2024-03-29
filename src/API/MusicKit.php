<?php

namespace API;

use Exception;
use Psr\Http\Message\ResponseInterface;

class MusicKit extends AbstractAPI {
	protected string $name = 'MusicKit API';
	protected string $path = 'v1/me/';
	private string $music_kit_token = '';

	private ?int $limit = null;
	private string $offset = '';
	private string $l = '';


	public function __construct(string $music_kit_token = '',
								bool   $renew = false) {
		$this->music_kit_token = $music_kit_token;
		parent::__construct($renew);
	}

	public static function current(): self {
		$api = new self();
		return $api->setUserToken();
	}

	/**
	 * @throws Exception No user found
	 */
	public static function fromUser(int $id): self {
		$api = new self();
		return $api->setUserTokenViaId($id);
	}

	public function setUserToken(): self {
		return $this->setMusicKitToken($this->app->getUserToken());
	}

	/**
	 * @throws Exception No user found
	 */
	public function setUserTokenViaId(int $id): self {
		$user = $this->app->manager()->findOne2('users', ['id' => $id]);
		if(!$user) {
			throw new Exception('No user found');
		}
		return $this->setMusicKitToken($user['musickit_user_token']);
	}

	public function setMusicKitToken(string $music_kit_token): self {
		$this->music_kit_token = $music_kit_token;
		$this->init();
		return $this;
	}

	public function headers(): array {
		return array_merge(parent::headers(), [
			'Music-User-Token' => $this->music_kit_token
		]);
	}

	protected function initDeveloperToken(bool $renew = false): void {
		parent::initDeveloperToken($renew);

		// .env MUSIC_KIT_TOKEN
		if($this->app->env('MUSIC_KIT_TOKEN')) {
			$this->music_kit_token = $this->app->env('MUSIC_KIT_TOKEN');
		}
	}

	public function test(): APIResponse {
		return $this->get('/library/search', [
			'term' => 'test',
			'types' => 'library-songs',
		]);
	}


}