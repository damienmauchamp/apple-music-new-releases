<?php

namespace AppleMusic;

class Playlist {

	private $id;

	public function __construct($id) {
		$this->id = $id;
	}

	/**
	 * Add a track to the playlist
	 * @param string $song_id
	 * @param string $music_user_token
	 * @param bool $debug
	 * @return bool
	 */
	public function addSong(string $song_id, string $music_user_token, bool $debug = false): bool {
		$api = new AppleMusicAPI(null, $music_user_token);
		$result = $api->post("/me/library/playlists/{$this->id}/tracks", [
			'id' => $song_id,
			'type' => 'songs',
		]);
		if($debug) {
			echo '<pre>'.print_r($result, true).'</pre>';
		}
		return ((int) $result['status']) === 204;
	}

	/**
	 * Add multiple tracks to the playlist
	 * @param array $songs_ids
	 * @param string $music_user_token
	 * @param bool $debug
	 * @return bool
	 */
	public function addSongs(array $songs_ids, string $music_user_token, bool $debug = false): bool {
		$api = new AppleMusicAPI(null, $music_user_token);
		$result = $api->post("/me/library/playlists/{$this->id}/tracks", [
			'data' => array_map(function ($song_id) {
				return [
					'id' => $song_id,
					'type' => 'songs',
				];
			}, $songs_ids),
		]);
		if($debug) {
			echo '<pre>'.print_r($result, true).'</pre>';
		}
		return ((int) $result['status']) === 204;
	}

}