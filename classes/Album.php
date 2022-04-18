<?php

namespace AppleMusic;

use AppleMusic\DB as db;

class Album extends AbstractItem {
	private $id;
	private $name;
	private $artistName;
	protected $date;
	private $artwork;
	private $link;
	private $explicit;
	private $added;

	public function __construct($id = null) {
		$this->id = $id;

	}

	public static function withArray($array) {
		$instance = new self();
		$instance->fill($array);
		return $instance;
	}

	protected function fill($array) {
		$this->id = $array["id"];
		$this->name = $array["name"];
		$this->artistName = $array["artistName"];
		$this->date = $array["date"];
		$this->artwork = $array["artwork"];
		$this->link = "https://music.apple.com/fr/album/".preg_replace('/-{2,}/', '-', trim(preg_replace('/[^\w-]/', '-', strtolower($array["name"])), "-"))."/".$array["id"];
		$this->explicit = $array["explicit"];
		$this->added = isset($array["added"]) ? $array["added"] : '';
	}

	public function addAlbum($idArtist) {
		$db = new db;
		return $db->addAlbum($this, $idArtist);
	}

	public static function objectToArray($obj) {
		return array(
			"id" => $obj->id,
			"name" => $obj->name,
			"artistName" => $obj->artistName,
			"date" => $obj->date,
			"artwork" => $obj->artwork,
			"explicit" => $obj->explicit,
			"added" => $obj->added,
		);
	}

	public function getDate($option = "") {
		if($option === "string") {
			$timestamp = strtotime($this->date);
			return date("d", $timestamp)." ".getMonth(date("m", $timestamp), true)." ".date("Y", $timestamp);
		}
		return $this->date;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getArtistName() {
		return $this->artistName;
	}

	/**
	 * @param int $width
	 * @return mixed
	 */
	public function getArtwork($width = 100) {
		return str_replace("100x100bb.jpg", "{$width}x{$width}bb.jpg", $this->removeHttps($this->artwork));
	}

	private function removeHttps($link) {
		return str_replace("http:", "", str_replace("https:", "", $link));
	}

	public function getLink($itmss = false) {
		if($itmss) {
			return preg_replace('/^https?/', 'itmss', $this->link);
		}
		return $this->link;
	}

	/**
	 * @return bool
	 */
	public function isExplicit() {
		return $this->explicit;
	}

	public function isOnPreorder() {
		return strtotime(date(DEFAULT_DATE_FORMAT." 00:00:00")) < strtotime(fixTZDate($this->date));
	}

	public function toString($newDisplay = null, $idArtist = null) {
		global $display;

		$display = $newDisplay ? $newDisplay : $display;
		$preorder = $this->isOnPreorder();
		$style = '<style>#album-'.$this->id.' .artwork:after { content: "'.$this->getDate("string").'" }</style>';

		return '
        <a href="'.$this->getLink().'" data-link="'.$this->getLink().'" data-itunes-link="'.$this->getLink(true).'" target="_blank"
           id="album-'.$this->id.'"
           data-am-kind="album"
           data-am-album-id="'.$this->id.'"
           '.($idArtist ? 'data-am-artist-id="'.$idArtist.'"' : '').'
           data-added="'.$this->added.'"
           class="album '.($preorder ? "preorder" : null).' we-lockup '.($display == "row" ? null : "l-column--grid").' targeted-link l-column small-'.(in_array($display, ["row", "grid-2-row"]) ? "2" : "6").' medium-3 large-2 ember-view"
           title="'.str_replace('"', '&quot;', $this->name).' by '.str_replace('"', '&quot;', $this->artistName).'">
            <picture
                    class="artwork we-lockup__artwork we-artwork--lockup we-artwork--fullwidth we-artwork ember-view">
                <img src="'.$this->getArtwork(500).'" loading="lazy"
                     style="background-color: transparent;" class="we-artwork__image artwork-img" alt="">
            </picture>

            <h3 class="album-title we-lockup__title '.($this->isExplicit() ? "icon icon-after icon-explicit" : null).'">
                <div class="we-truncate targeted-link__target we-truncate--single-line ember-view">
                    '.$this->name.'
                </div>
            </h3>

            <h4 class="album-subtitle we-truncate we-truncate--single-line we-lockup__subtitle targeted-link__target">
                '.$this->artistName.'
            </h4>
            
            '.($preorder ? $style : null).'
        </a>';
	}

	public function getCalalogSongs(): array {
		$api = new AppleMusicAPI();
		try {
			$response = $api->get("/catalog/{$api->getStorefront()}/albums/{$this->id}/tracks");
			return ($response['body'] ?? [])['data'] ?? [];
		} catch(\Exception $e) {
			echo '<pre>'.print_r($e, true).'</pre>';
			return [];
		}
	}

	public function addSongsToPlaylist(): void {

		$songs_ids = array_map(function ($song) {
			return $song['id'];
		}, $this->getCalalogSongs());

		try {
			$user = User::getCurrentUser();
			if($user && $user->musicKitTokenIsValid() && $user->playlistIsSet()) {
				$user->getPlaylist()->addSongs($songs_ids, $user->getMusicKitToken());
			}
		} catch(\Exception $e) {
//			echo $e->getMessage();
		}
	}
}