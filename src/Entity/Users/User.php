<?php

namespace Entity\Users;

use DateInterval;
use DateTime;
use Exception;
use src\AbstractElement;
use src\Database\DBTrait;

class User extends AbstractElement {

//	use DBTrait;

	private ?int $id;
	private string $username;
	private string $firstname;
	private string $email;
	private ?string $music_token = null;
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
		$manager = $this->app->manager();
		$data = $manager->findOne2('users', ['id' => $this->id]);
		if(!$data) {
			// todo : define exception and logout
			throw new Exception("Cannot load user");
		}

		$this->username = $data['username'];
		$this->firstname = $data['prenom'];
		$this->email = $data['mail'];

		$this->setMusicToken($data['musickit_user_token'], null, $data['date_generated']);
	}

	public function setMusicToken(string $music_token, ?string $date_expired, ?string $date_created): self {
		$this->music_token = $music_token;
		if(!$this->music_token) {
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
		return !$this->music_token || $this->music_token_expiracy < new DateTime();
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

}