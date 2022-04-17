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

}