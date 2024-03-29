<?php
/**
 * Created by PhpStorm.
 * User: dmauchamp
 * Date: 10/09/2018
 * Time: 10:05
 */

namespace AppleMusic;

use AppleMusic\DB as db;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class Song extends AbstractItem {
	private $id;
	private $collectionId;
	private $trackName;
	private $collectionName;
	private $artistName;
	protected $date;
	private $artwork;
	private $artistLink;
	private $albumLink;
	private $link;
	private $explicit;
	private $isStreamable;

	const LOG_FILE = DEFAULT_PATH . '/logs/songs.log';
	private $logger;

	public function __construct() {

	}

	public static function withArray($array) {
		$instance = new static();
		$instance->fill($array);

		return $instance;
	}

	public function getArtistId() {
		$db = new db();

		return $db->getArtistIdFromSong($this->id);
	}

	protected function fill($array) {
		$this->id = $array["id"];
		$this->collectionId = $array["collectionId"];
		$this->collectionName = $array["collectionName"];
		$this->trackName = $array["trackName"];
		$this->artistName = $array["artistName"];
		$this->date = $array["date"];
		$this->artwork = $array["artwork"];
		$this->artistLink = "https://music.apple.com/fr/artist/" . $this->getArtistId();
		$this->albumLink = "https://music.apple.com/fr/album/" . $array["collectionId"];
		$this->link = "https://music.apple.com/fr/album/" . $array["collectionId"] . "?i=" . $array["id"];
		$this->explicit = $array["explicit"];
		$this->isStreamable = $array["isStreamable"];
	}

	/**
	 * Add or update a song in the database
	 * @param $idArtist
	 * @return bool
	 */
	public function addSong($idArtist): bool {
		$db = new db();
		$new = $this->isNew();
		$added = $this->isAdded();

		if ($new && !$added) {
			$this->addToPlaylist();
		} else if ($new) {
			$icon = "👍";
			$log = "{$icon} [addSong] {$this->id} - {$this->trackName} by {$this->artistName}: already in database";
			$this->log($log);
		}

		return $added || $db->addSong($this, $idArtist);
	}

	public function isAdded(): ?bool {
		$db = new db();

		return $db->songExists($this->id);
	}

	/**
	 * @return bool
	 */
	protected function addToPlaylist(): bool {
		$log = "[addSong] {$this->id} - {$this->trackName} by {$this->artistName} [released: {$this->date}]: ";

		try {
			$user = User::getCurrentUser();
			if ($user && $user->musicKitTokenIsValid() && $user->playlistIsSet()) {
				$added = $user->getPlaylist()->addSong($this->id, $user->getMusicKitToken());
				$icon = $added ? "❌" : "🟢";
				$this->log("{$icon} {$log}" . ($added ? "Added to playlist" : 'Fail to add to playlist (Sm1)'),
					$added ? 'info' : 'error',
					$added ? [] : [
						'music-token' => $user->getMusicKitToken(),
						'playlist' => $user->getPlaylist(),
					]);
			} else {
				$log .= "User not found or token not valid (Sx1)";
				$this->log("⚠ {$log}", 'error', [
					'user' => $user,
					'token' => $user->musicKitTokenIsValid(),
					'playlist' => $user->playlistIsSet(),
				]);

				return false;
			}
		} catch (\Exception $e) {
			$log .= "Error: " . $e->getMessage() . " (Sx2)";
			$this->log("⚠ {$log}", 'error');

			return false;
		}

		return true;

	}

	public static function objectToArray($obj) {
		return [
			"id" => $obj->id,
			"collectionId" => $obj->collectionId,
			"collectionName" => $obj->collectionName,
			"trackName" => $obj->trackName,
			"artistName" => $obj->artistName,
			"date" => $obj->date,
			"artwork" => $obj->artwork,
			"explicit" => $obj->explicit,
			"isStreamable" => $obj->isStreamable,
		];
	}

	public function getDate($option = "") {
		if ($option === "string") {
			$timestamp = strtotime($this->date);

			return date("d", $timestamp) . " " . getMonth(date("m", $timestamp), true) . " " . date("Y", $timestamp);
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
	public function getCollectionId() {
		return $this->collectionId;
	}

	/**
	 * @return mixed
	 */
	public function getCollectionName() {
		return $this->collectionName;
	}

	public function getTrackName() {
		return $this->trackName;
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
		if ($itmss) {
			return preg_replace('/^https?/', 'itmss', $this->link);
		}

		return $this->link;
	}

	/**
	 * @return mixed
	 */
	public function isExplicit() {
		return $this->explicit;
	}

	public function isStreamable() {
		return !$this->isStreamable;
	}

	public function isOnPreorder() {
		return strtotime(date(DEFAULT_DATE_FORMAT . " 00:00:00")) < strtotime($this->date);
	}

	public function toString($newDisplay = false) {
		$tr = $newDisplay ? 'div' : 'tr';
		$td = $newDisplay ? 'div' : 'td';

		return '
        <' . $tr . ' data-date="' . $this->date . '" data-link="' . $this->getLink() . '" data-itunes-link="' . $this->getLink(true) . '" id="ember988" class="song table__row  we-selectable-item ' . ($this->isStreamable() ? 'is-available we-selectable-item--allows-interaction' : 'on-preorder') . ' ember-view" title="' . (!$this->isStreamable() ? intval(date("d", strtotime($this->date) - strtotime("now"))) . " jours" : null) . '">
            <' . $td . ' class="table__row__artwork">
                <a href="' . $this->link . '" target="_blank">
                    <picture id="ember989" class="we-artwork--less-round we-artwork ember-view">
                        <img class="we-artwork__image ember989" src="' . $this->getArtwork(144) . '" loading="lazy" style="background-color: #251637;" alt="">
                    </picture>
                </a>
            </' . $td . '>
            <' . $td . ' class="table__row__name">
                <a href="' . $this->link . '" target="_blank" class="table__row__link targeted-link targeted-link--no-monochrome-underline" data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;card&quot;,&quot;targetId&quot;:&quot;1321217032&quot;}">
                    <div class="spread icon icon-after ' . ($this->explicit ? 'icon-explicit' : '') . ' table__row__explicit">
                        <span id="ember995" class="table__row__headline targeted-link__target we-truncate we-truncate--single-line ember-view">' . $this->getTrackName() . '</span>
                    </div>
                </a>
                <a href="' . $this->link . '" target="_blank" class="table__row__link table__row__link--secondary large-hide" data-metrics-click="{&quot;actionType&quot;:&quot;navigate&quot;,&quot;targetType&quot;:&quot;button&quot;,&quot;targetId&quot;:&quot;LinkToArtist&quot;}">
                    <div class="spread">
                        <span id="ember996" class="we-truncate we-truncate--single-line ember-view">' . $this->getArtistName() . '</span>
                    </div>
                </a>
            </' . $td . '>
            <' . $td . ' class="table__row__artist small-hide large-show-tablecell">
                <a href="' . $this->artistLink . '" target="_blank" class="table__row__link table__row__link--secondary">
                    <div class="spread">
                        <span id="ember997" class="we-truncate we-truncate--single-line ember-view">' . $this->getArtistName() . '</span>
                    </div>
                </a>
            </' . $td . '>
            <' . $td . ' class="table__row__album small-hide medium-show-tablecell">
                <a href="' . $this->albumLink . '" target="_blank" class="table__row__link table__row__link--secondary">
                    <div class="spread">
                        <span id="ember998" class="we-truncate we-truncate--single-line ember-view">' . $this->getCollectionName() . '</span>
                    </div>
                </a>
            </' . $td . '>

            <' . $td . ' class="table__row__duration" data-duration-width="-0:00">
                <time data-test-we-duration="" datetime="PT3M37S" aria-label="DURÉE" class="table__row__duration-counter">
                    ' . date("d/m", strtotime($this->date)) . '
                </time>
            </' . $td . '>
        </' . $tr . '>
        ';
	}

	private function log(string $message, string $type = 'info', array $context = []): void {
		if (!$this->logger) {
			$this->logger = new Logger('Songs');
			$this->logger->pushHandler(new RotatingFileHandler(self::LOG_FILE, 7, Logger::DEBUG));
		}

		$this->logger->$type($message, $context);
	}

}
