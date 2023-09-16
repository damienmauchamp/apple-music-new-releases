<?php

namespace Entity\Users;

use API\MusicKit;
use DateInterval;
use DateTime;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use src\AbstractElement;

class User extends AbstractElement {

//	use DBTrait;

	private ?int $id;
	private string $username;
	private string $firstname;
	private string $email;
	private ?string $music_kit_token = null;
	private ?DateTime $music_token_created = null;
	private ?DateTime $music_token_expiracy = null;

	public function __construct(?int $id) {
		parent::__construct();
		$this->id = $id;
		$this->load();
	}

	/**
	 * @throws Exception
	 */
	private function load() {
		$data = $this->app->manager()->findOne2('users', ['id' => $this->id]);
		if(!$data) {
			// todo : define exception and logout
			throw new Exception("Cannot load user");
		}

		$this->username = $data['username'];
		$this->firstname = $data['prenom'];
		$this->email = $data['mail'];

		$this->setMusicKitToken($data['musickit_user_token'], null, $data['date_generated']);
	}

	public function setMusicKitToken(string $music_token, ?string $date_expired, ?string $date_created): self {
		$this->music_kit_token = $music_token;
		if(!$this->music_kit_token) {
			return $this;
		}

		$interval = new DateInterval('P6M');
		if($date_created && !$date_expired) {
			$datetime_created = new DateTime($date_created);
			$datetime_expired = (clone $datetime_created)->add($interval);

			$this->music_token_created = $datetime_created;
			$this->music_token_expiracy = $datetime_expired;
		}
		else {
			$this->music_token_created = $date_expired ? new DateTime($date_expired) : '';
			$this->music_token_expiracy = $date_created ? new DateTime($date_created) : '';
		}
		return $this;
	}

	public function musicTokenExpired(): bool {
		return !$this->music_kit_token || $this->music_token_expiracy < new DateTime();
	}

	public static function current(): ?self {
		$id = isset($_SESSION['id_user']) && $_SESSION['id_user'] ? ((int) $_SESSION['id_user']) : null;
		if(!$id) {
			return null;
		}
		return new self($id);
	}

	public function getId(): ?int {
		return $this->id;
	}


	public function getFirstName(): string {
		return $this->firstname;
	}

	/**
	 * @throws Exception
	 */
	public function updateToken(): self {
		$res = $this->app->manager()->update('users', [
			'musickit_user_token' => $this->music_kit_token,
			'date_generated' => $this->music_token_created->format('Y-m-d H:i:s.u'),
		], ['id' => $this->id]);

		if(!$res) {
			throw new Exception('Error while saving new MusicKit token');
		}
		return $this;
	}

	public function getMusicKitToken(): string {
		return $this->music_kit_token;
	}

	public function getTokenCreationDate(): ?DateTime {
		return $this->music_token_created;
	}

	public function getTokenCreationDateToString(string $format = 'Y-m-d H:i:s'): string {
		return $this->music_token_created ? $this->music_token_created->format($format) : '';
	}

	public function getTokenExpirationDate(): ?DateTime {
		return $this->music_token_expiracy;
	}

	public function getTokenExpirationDateToString(string $format = 'Y-m-d H:i:s'): string {
		return $this->music_token_expiracy ? $this->music_token_expiracy->format($format) : '';
	}

	public function isValidToken(): bool {

		try {
			$api = MusicKit::fromUser($this->id);
		} catch(Exception $e) {
			return false;
		}

		try {
			$api->test();
		} catch(GuzzleException $e) {
			return false;
		}
		
		return true;
	}
}