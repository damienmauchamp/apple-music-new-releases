<?php

namespace AppleMusic;

use DateTime;
use Exception;

class User {

	private $username;
	private $token;
	private $music_user_token;
	private $date_generated;
	private $playlist;
	private $add_to_playlist;

	const TOKEN_VALIDITY_DAYS = '180';

	public function __construct($username, $token, $music_user_token, $date_generated, $playlist_id, $add_to_playlist) {
		$this->username = $username;
		$this->token = $token;
		$this->music_user_token = $music_user_token;
		try {
			$this->date_generated = $date_generated ? new DateTime($date_generated) : null;
		} catch(Exception $e) {
			$this->date_generated = null;
		}

		$this->playlist = $playlist_id ? new Playlist($playlist_id) : null;
		$this->add_to_playlist = $add_to_playlist;
	}

	public static function getUserWithID(int $id): ?User {
		$db = new DB();
		$data = $db->selectPerso("SELECT * FROM users WHERE id = :id", ['id' => $id])[0] ?? null;
		if(!$data) {
			return null;
		}

		return new User($data['username'],
			$data['token'],
			$data['musickit_user_token'] ?? null,
			$data['date_generated'] ?? null,
			$data['playlist_id'] ?? null,
			$data['add_to_playlist'] ?? false);
	}

	public static function getUser(string $username): ?User {
		$db = new DB();
		$data = $db->selectPerso("SELECT * FROM users WHERE username = :username", ['username' => $username])[0] ?? null;
		if(!$data) {
			return null;
		}

		return new User($data['username'],
			$data['token'],
			$data['musickit_user_token'] ?? null,
			$data['date_generated'] ?? null,
			$data['playlist_id'] ?? null,
			$data['add_to_playlist'] ?? false);
	}

	public static function getUserID(): ?int {
		return $_SESSION['id_user'] ?? null;
	}

	public static function getCurrentUser(): ?User {
		return self::getUserWithID(self::getUserID());
	}

	public function getPlaylist(): ?Playlist {
		return $this->playlist;
	}

	public function getMusicKitToken(): string {
		return $this->music_user_token;
	}

	public function musicKitTokenIsSet(): bool {
		return $this->date_generated && $this->music_user_token;
	}

	public function musicKitTokenIsValid(): bool {
		return $this->musicKitTokenIsSet() && (new DateTime)->diff($this->date_generated)->d < self::TOKEN_VALIDITY_DAYS;
	}

	public function playlistIsSet(): bool {
		return $this->playlist && $this->add_to_playlist;
	}


}