<?php

namespace AppleMusic;

use Dotenv;
use PDO;
use PDOException;

class DB {
	/**
	 * @var PDO null
	 */
	protected $dbh;

	/**
	 * DB constructor.
	 */
	public function __construct() {
		$this->dbh = null;
	}

	/**
	 * Connexion à la base de données
	 */
	private function connect() {

		// loading .env data
		if(is_file(dirname(__DIR__).'/.env')) {
			$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
			$dotenv->load();
		}

		$DB_serveur = !empty($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : "localhost";
		$DB_port = !empty($_ENV['DB_PORT']) ? $_ENV['DB_PORT'] : "3306";
		$DB_nom = !empty($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : 'applemusic-update';
		$DB_login = !empty($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : 'root';
		$DB_psw = !empty($_ENV['DB_PWD']) ? $_ENV['DB_PWD'] : '';

		try {
			$this->dbh = new PDO("mysql:host={$DB_serveur};port={$DB_port};dbname={$DB_nom}", $DB_login, $DB_psw);
			$this->dbh->exec('SET CHARACTER SET utf8');
			$this->dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			echo "Erreur ! : ".$e->getMessage()."<br/>";
			die("Connexion impossible à la base de données.".$e->getMessage());
		}
	}

	/**
	 * Déconnexion de la base de données
	 */
	private function disconnect() {
		$this->dbh = null;
	}

	private function getEnv() {
		return file_get_contents(dirname(__DIR__).'/.env');
	}

	// GET

	public function getUserAlbums($userId = false) {
		$userId = $userId ?: $_SESSION['id_user'];

		$sql = "
			SELECT
			  al.id AS id, al.name AS name, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
			  ar.id AS idArtist, ua.lastUpdate AS lastUpdate, al.explicit AS explicit, al.added AS added
			FROM albums al
			  INNER JOIN artists_albums aa ON al.id = aa.idAlbum
			  INNER JOIN artists ar ON ar.id = aa.idArtist
			  INNER JOIN users_artists ua ON ua.idArtist = ar.id
			WHERE ua.idUser = :id_user AND ua.lastUpdate <= al.date AND ua.active = 1
				AND al.date < DATE_ADD(NOW(), INTERVAL 1 YEAR)
			ORDER BY ar.name ASC, al.date DESC";

		$this->connect();
		//		$stmt = $this->dbh->query($sql);
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue("id_user", $userId);
		$stmt->execute();
		$this->disconnect();

		$res = $stmt->fetchAll();

		return $res ? json_encode($res) : null;
	}

	public function getUserWeekReleases($type = null, $userId = false) {
		$userId = $userId ?: $_SESSION['id_user'];

		$complement_sql = '';
		switch($type) {
			case 'albums':
				$complement_sql = "AND al.name NOT REGEXP '- Single'";
				break;
			case 'singles':
				$complement_sql = "AND al.name REGEXP '- Single\s*$'";
				break;
		}

		$sql = "
			SELECT
			  al.id AS id, al.name AS name, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
			  ar.id AS idArtist, ua.lastUpdate AS lastUpdate, al.explicit AS explicit, al.added AS added
			FROM albums al
			  INNER JOIN artists_albums aa ON al.id = aa.idAlbum
			  INNER JOIN artists ar ON ar.id = aa.idArtist
			  INNER JOIN users_artists ua ON ua.idArtist = ar.id
			WHERE ua.idUser = :id_user AND ua.lastUpdate <= al.date AND ua.active = 1 AND DATE_SUB(NOW(), INTERVAL 7 DAY) <= al.date AND al.date <= NOW()
				AND al.date < DATE_ADD(NOW(), INTERVAL 1 YEAR)
				{$complement_sql}
			GROUP BY al.id
			ORDER BY al.date DESC, al.added DESC, ar.name ASC, al.explicit DESC";

		$this->connect();
		//		$stmt = $this->dbh->query($sql);
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue("id_user", $userId);
		$stmt->execute();
		$this->disconnect();

		$res = $stmt->fetchAll();

		return $res ? json_encode($res) : null;
	}

	public function getUserUpcomingReleases($userId = false) {
		$userId = $userId ?: $_SESSION['id_user'];

		$sql = "
			SELECT
			  al.id AS id, al.name AS name, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
			  ar.id AS idArtist, ua.lastUpdate AS lastUpdate, al.explicit AS explicit, al.added AS added
			FROM albums al
			  INNER JOIN artists_albums aa ON al.id = aa.idAlbum
			  INNER JOIN artists ar ON ar.id = aa.idArtist
			  INNER JOIN users_artists ua ON ua.idArtist = ar.id
			WHERE ua.idUser = :id_user
				AND ua.lastUpdate <= al.date
				AND ua.active = 1
				-- AND DATE_SUB(NOW(), INTERVAL 7 DAY) <= al.date
				AND al.date > NOW()
				AND al.date < DATE_ADD(NOW(), INTERVAL 1 YEAR)
			GROUP BY al.id
			ORDER BY al.date ASC, al.added DESC, ar.name ASC, al.explicit DESC";

		$this->connect();
		//		$stmt = $this->dbh->query($sql);
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue("id_user", $userId);
		$stmt->execute();
		$this->disconnect();

		$res = $stmt->fetchAll();

		return $res ? json_encode($res) : null;
	}

	public function getUserSongs($days = 7, $userId = false, ?bool $compilation = null) {
		$userId = $userId ?: $_SESSION['id_user'];

		$compilation_sql = $compilation !== null ? "AND IFNULL(albums.isCompilation, 0) = :compilation" : "";

		$sql = "
			SELECT
			  al.id AS id, al.collectionId AS collectionId, al.collectionName AS collectionName, al.trackName AS trackName, al.artistName AS artistName, al.date AS date, al.artwork AS artwork,
			  ar.id AS idArtist, ua.lastUpdate AS lastUpdate, al.explicit AS explicit, al.isStreamable AS isStreamable, IFNULL(albums.isCompilation, 0) as isCompilation	
			FROM songs al
			  INNER JOIN artists_songs aa ON al.id = aa.idAlbum
			  INNER JOIN artists ar ON ar.id = aa.idArtist
			  INNER JOIN users_artists ua ON ua.idArtist = ar.id AND ua.idUser = :id_user AND ua.active = 1
			  LEFT JOIN albums ON albums.id = al.collectionId
			
			WHERE al.date >= DATE_SUB(NOW(), INTERVAL :n_days DAY)
				AND al.date < DATE_ADD(NOW(), INTERVAL 1 YEAR) {$compilation_sql}
			GROUP BY id
			ORDER BY al.isStreamable ASC, al.date DESC, al.date DESC, al.collectionName, al.collectionId, ar.name ASC";

		$this->connect();
		//		$stmt = $this->dbh->query($sql);
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue("id_user", $userId);
		$stmt->bindValue("n_days", $days);
		if($compilation !== null) {
			$stmt->bindValue("compilation", $compilation);
		}
		$stmt->execute();
		$this->disconnect();

		//		$res = $stmt->rowCount() > 0 ? $stmt->fetchAll() : null;
		$res = $stmt->fetchAll();

		return $res ? json_encode($res) : null;
	}

	public function getUsersArtists($userId = false, $lastUpdate = true, ?int $idArtist = null) {
		$userId = $userId ?: $_SESSION['id_user'];

		$lastUpdateStr = $lastUpdate ? ", ua.lastUpdate" : "";

		$idArtistStr = $idArtist ? "AND ar.id = :idArtist" : '';

		$sql =
			"SELECT ar.id, ar.name$lastUpdateStr
			FROM artists ar
			  INNER JOIN users_artists ua ON ua.idArtist = ar.id AND ua.active = 1
			WHERE ua.idUser = :id_user {$idArtistStr}
			ORDER BY name;";

		$this->connect();
		//		$stmt = $this->dbh->query($sql);
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue("id_user", $userId);
		if($idArtist) {
			$stmt->bindValue("idArtist", $idArtist);
		}
		$stmt->execute();
		$this->disconnect();

		$res = $stmt->fetchAll();

		return $res ? json_encode($res) : null;
	}

	public function getUserArtist($idArtist, $userId = 0) {
		$userId = $userId ?: $_SESSION['id_user'];

		$sql = "
			SELECT ar.id, ar.name, ua.lastUpdate
			FROM artists ar
			INNER JOIN users_artists ua
				ON ua.idUser = :id_user
				AND ua.idArtist = :id_artist
			WHERE ar.id = :id_artist;";

		$this->connect();
		$stmt = $this->dbh->prepare($sql);
		// $stmt->bindValue("id_artist", $idArtist);
		$stmt->execute([
			'id_artist' => $idArtist,
			'id_user' => $userId,
		]);
		// echo print_r($this->dbh->errorInfo(), true);
		$this->disconnect();

		$res = $stmt->fetch();

		return $res ? json_encode($res) : null;
	}

	// POST

	/**
	 * @param Artist $artist
	 * @return bool
	 */
	public function addArtist($artist, $userId = null) {
		$userId = $userId ?: $_SESSION['id_user'];
		if($userId < 0) {
			return false;
		}

		$id = $artist->getId();
		$name = addslashes($artist->getName());
		$sqlArtist = "
			INSERT INTO artists (id, name)
			VALUES (:id, :name)
			ON DUPLICATE KEY UPDATE
				id = :id,
				name = :name";

		$sqlUserArtist = "
			INSERT INTO users_artists (idUser, idArtist, lastUpdate, active)
			VALUES (:id_user, :id, CONCAT(DATE(NOW()),' 00:00:00'), 1)
			ON DUPLICATE KEY UPDATE
				idUser = :id_user,
				idArtist = :id";

		$this->connect();
		$stmt = $this->dbh->prepare($sqlArtist);
		$resArtist = $stmt->execute([
			'id' => $id,
			'name' => $name,
		]);
		$stmt = $this->dbh->prepare($sqlUserArtist);
		$resUserArtist = $stmt->execute([
			'id_user' => (int) $userId,
			'id' => $id,
		]);
		$this->disconnect();
		//		var_dump($sqlArtist, $sqlUserArtist);exit;
		//		echo json_encode($sqlUserArtist);

		return $resArtist && $resUserArtist;
	}

	/**
	 * @param Album $album
	 * @param $idArtist
	 * @return bool
	 */
	public function addAlbum($album, $idArtist) {
		$id = $album->getId();
		$name = $album->getName();
		$artistName = $album->getArtistName();
		$date = fixTZDate($album->getDate());
		$artwork = $album->getArtwork();
		$explicit = $album->isExplicit() ? 1 : 0;
		$isCompilation = $album->isCompilation();
		// $added = new \DateTime();

		$sqlAlbum = "
			INSERT INTO albums (id, name, artistName, date, artwork, explicit, isCompilation)
			VALUES (:id, :name, :artist_name, :date, :artwork, :explicit, :isCompilation)
			ON DUPLICATE KEY UPDATE
				name = :name,
				artistName = :artist_name,
				date = IF(custom = 0, :date, date),
				artwork = :artwork,
				explicit = :explicit";

		$sqlArtistAlbum = "
			INSERT INTO artists_albums (idArtist, idAlbum)
			VALUES (:id_artist, :id_album)
			ON DUPLICATE KEY UPDATE idArtist = :id_artist, idAlbum = :id_album";

		$this->connect();
		try {
			$stmt = $this->dbh->prepare($sqlAlbum);
			$resAlbum = $stmt->execute([
				'id' => $id,
				'name' => $name,
				'artist_name' => $artistName,
				'date' => $date,
				'artwork' => $artwork,
				'explicit' => $explicit,
				'isCompilation' => $isCompilation,
				// 'added' => $added->format('Y-m-d H:i:s')
			]);

			$stmt = $this->dbh->prepare($sqlArtistAlbum);
			$resArtistAlbum = $stmt->execute([
				'id_artist' => $idArtist,
				'id_album' => $id,
			]);
		} catch(PDOException $e) {
			echo "\nALBUM ERROR: ".json_encode([
					'erreur' => $e->getMessage(),
					'id' => $id,
					'name' => $name,
					'artist_name' => $artistName,
					'date' => $date,
					'artwork' => $artwork,
					'explicit' => $explicit,
					'isCompilation' => $isCompilation,
					// 'added' => $added->format('Y-m-d H:i:s')
				])."\n";

			$resAlbum = $resArtistAlbum = false;
		}
		$this->disconnect();

		return $resAlbum && $resArtistAlbum;
	}

	/**
	 * @param Song $song
	 * @param $idArtist
	 * @return bool
	 */
	public function addSong($song, $idArtist) {
		$id = $song->getId();
		$collectionId = $song->getCollectionId();
		$collectionName = $song->getCollectionName();
		$trackName = $song->getTrackName();
		$artistName = $song->getArtistName();
		$date = fixTZDate($song->getDate());
		$artwork = $song->getArtwork();
		$explicit = $song->isExplicit() ? 1 : 0;
		$isStreamable = $song->isStreamable() ? 1 : 0;
		// $added = new \DateTime();

		$sqlAlbum = "
			INSERT INTO songs (id, collectionId, collectionName, trackName, artistName, date, artwork, explicit, isStreamable)
			VALUES (:id, :collection_id, :collection_name, :track_name, :artist_name, :date, :artwork, :explicit, :isStreamable)
			ON DUPLICATE KEY UPDATE
				id = :id,
				collectionName = :collection_name,
				trackName = :track_name,
				artistName = :artist_name,
				date = IF(custom = 0, :date, date),
				artwork = :artwork,
				explicit = :explicit,
				isStreamable = :isStreamable";

		$sqlArtistAlbum = "
			INSERT INTO artists_songs (idArtist, idAlbum)
			VALUES (:id_artist, :id)
			ON DUPLICATE KEY UPDATE idArtist = :id_artist, idAlbum = :id";

		/*if (strstr($artistName, 'Denzel')) {
		// file_put_contents(LOG_FILE, "\nSONG ADDED: " . json_encode(array(
		echo "\nSONG ADDED: " . json_encode(array(
		'id' => $id,
		'collection_id' => $collectionId,
		'collection_name' => $collectionName,
		'track_name' => utf8_encode($trackName),
		'artist_name' => $artistName,
		'$song->getDate()' => $song->getDate(),
		'date' => $date,
		'datetime' => new \DateTime($song->getDate()),
		'datetime2' => new \DateTime($date),
		'artwork' => $artwork,
		'explicit' => $explicit,
		'isStreamable' => $isStreamable,
		// 'added' => $added->format('Y-m-d H:i:s')
		)) . "\n";
		}*/

		$this->connect();
		try {
			$stmt = $this->dbh->prepare($sqlAlbum);
			$resAlbum = $stmt->execute([
				'id' => $id,
				'collection_id' => $collectionId,
				'collection_name' => $collectionName,
				'track_name' => $trackName,
				'artist_name' => $artistName,
				'date' => $date,
				'artwork' => $artwork,
				'explicit' => $explicit,
				'isStreamable' => $isStreamable,
				// 'added' => $added->format('Y-m-d H:i:s')
			]);
			$stmt = $this->dbh->prepare($sqlArtistAlbum);
			$resArtistAlbum = $stmt->execute([
				'id' => $id,
				'id_artist' => $idArtist,
			]);
		} catch(PDOException $e) {
			echo "\nSONG ERROR: ".json_encode([
					'erreur' => $e->getMessage(),
					'id' => $id,
					'collection_id' => $collectionId,
					'collection_name' => $collectionName,
					'track_name' => $trackName,
					'artist_name' => $artistName,
					'$song->getDate()' => $song->getDate(),
					'date' => $date,
					'datetime' => new \DateTime($song->getDate()),
					'datetime2' => new \DateTime($date),
					'artwork' => $artwork,
					'explicit' => $explicit,
					'isStreamable' => $isStreamable,
					// 'added' => $added->format('Y-m-d H:i:s')
				])."\n";

			$resAlbum = $resArtistAlbum = false;
		}
		$this->disconnect();

		return $resAlbum && $resArtistAlbum;
	}

	public function artistUpdated($idArtist, $minDate) {

		$userId = $_SESSION['id_user'] ?? 0;

		//		$date = "NOW()";
		$date = date("Y-m-d 00:00:00", strtotime($minDate));
		$sql = "
			UPDATE users_artists
			SET lastUpdate = :date
			WHERE idArtist = :id_artist AND idUser = :id_user";
		$this->connect();
		$stmt = $this->dbh->prepare($sql);
		$res = $stmt->execute([
			'date' => $date,
			'id_artist' => $idArtist,
			'id_user' => $userId,
		]);
		$this->disconnect();

		return $res;
	}

	public function getArtist($idArtist) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT ar.id AS id, ar.name AS name, ua.lastUpdate AS lastUpdate, ua.active AS active
			FROM artists ar
			  INNER JOIN users_artists ua ON ua.idArtist = ar.id
			WHERE ar.id = :id_artist"
		);
		$stmt->execute(["id_artist" => $idArtist]);
		$this->disconnect();

		$res = $stmt->fetch();

		return $res;
	}

	public function removeOldAlbums($days = 180, ?int $idArtist = null) {
		$userId = $_SESSION['id_user'] ?? 0;
		$this->disableForeignKeysCheck();
		$this->connect();
		/*$stmt = $this->dbh->prepare("
		DELETE aa, al
		FROM albums al
		INNER JOIN artists_albums aa ON al.id = aa.idAlbum
		INNER JOIN artists ar ON ar.id = aa.idArtist
		INNER JOIN users_artists ua ON ua.idArtist = ar.id AND ua.idUser = :id_user
		WHERE DATE_SUB(ua.lastUpdate, INTERVAL :days DAY) > al.date;"
		);
		$res = $stmt->execute(array("id_user" => $userId, "days" => $days));*/
		$stmt = $this->dbh->prepare(
			"
			DELETE aa, al
			FROM albums al
			  JOIN artists_albums aa ON al.id = aa.idAlbum
			  JOIN artists ar ON ar.id = aa.idArtist
			WHERE al.date < DATE_SUB(NOW(), INTERVAL :days DAY);"
		);
		$res = $stmt->execute(["days" => $days]);
		$this->disconnect();
		$this->enableForeignKeysCheck();

		return $res;
	}

	public function removeOldSongs($days = 90, ?int $idArtist = null) {
		$userId = $_SESSION['id_user'] ?? 0;
		$this->disableForeignKeysCheck();
		$this->connect();
		/*$stmt = $this->dbh->prepare("
		DELETE aa, al
		FROM songs al
		INNER JOIN artists_songs aa ON al.id = aa.idAlbum
		INNER JOIN artists ar ON ar.id = aa.idArtist
		INNER JOIN users_artists ua ON ua.idArtist = ar.id AND ua.idUser = :id_user
		WHERE DATE_SUB(ua.lastUpdate, INTERVAL :days DAY) > al.date;"
		);
		$res = $stmt->execute(array("id_user" => $user_id, "days" => $days));*/
		$stmt = $this->dbh->prepare(
			"
			DELETE aa, al
			FROM songs al
			  JOIN artists_songs aa ON al.id = aa.idAlbum
			  JOIN artists ar ON ar.id = aa.idArtist
			WHERE al.date < DATE_SUB(NOW(), INTERVAL :days DAY);"
		);
		$res = $stmt->execute(["days" => $days]);
		$this->disconnect();
		$this->enableForeignKeysCheck();

		return $res;
	}

	public function artistIsAdded($id) {
		$userId = $_SESSION['id_user'] ?? 0;
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT *
			FROM users_artists ua
			WHERE ua.idUser = :id_user AND ua.idArtist = :id_artist;"
		);
		$stmt->execute(["id_user" => $userId, "id_artist" => $id]);
		$res = $stmt->fetch();
		$this->disconnect();

		return $res ? false : true;
	}

	public function selectPerso($sql, array $params = []) {
		$this->connect();
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute($params) or die("Erreur dans la requête.");
		$this->disconnect();

		return $stmt->fetchAll();
	}

	// Functions

	protected function setResults($array) {
		$res = [];
		foreach($array as $key => $value) {
			if(!is_numeric($key)) {
				$res[$key] = $value;
			}

		}

		return json_encode($res);
	}

	public function editLastUpdated($days = 7, $id_user = 0) {
		$this->connect();
		$user_condition = "";
		$params = ["days" => $days];
		if($id_user > 0) {
			$user_condition = "WHERE ua.idUser = :id_user";
			$params['id_user'] = $id_user;
		}
		$stmt = $this->dbh->prepare(
			"
			UPDATE users_artists ua
			SET ua.lastUpdate = DATE_SUB(ua.lastUpdate, INTERVAL :days DAY)
			{$user_condition};"
		);
		$res = $stmt->execute($params);
		$this->disconnect();

		return $res;
	}

	public function logMail($description, $id_user) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			INSERT INTO logs (type, date, id_user)
			VALUES (:description, :date, :user);"
		);
		$res = $stmt->execute(["description" => $description, "date" => date("Y-m-d H:i:s"), "user" => $id_user]);
		$this->disconnect();

		return $res;
	}

	public function logRefresh($type = "") {
		$userId = $_SESSION['id_user'] ?? 0;
		$refresh = $type ? "refresh $type" : "refresh";
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			INSERT INTO logs (type, date, id_user)
			VALUES (:type, :date, :user);"
		);
		$res = $stmt->execute(["type" => $refresh, "date" => date("Y-m-d H:i:s"), "user" => $userId]);
		$this->disconnect();

		return $res;
	}

	public function getLastRefresh($userId = null) {
		$userId = $userId ?: $_SESSION['id_user'];
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT MAX(date)
			FROM logs
			WHERE id_user= :user;"
		);
		$stmt->execute(["user" => $userId]);
		$res = $stmt->fetch();
		$this->disconnect();

		return $res[0];
	}

	public function getNotificationsStatus($userId = null) {
		$userId = $userId ?: $_SESSION['id_user'];
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT notifications
			FROM users
			WHERE id= :user;"
		);
		$stmt->execute(["user" => $userId]);
		$res = $stmt->fetch();
		$this->disconnect();

		return $res[0];
	}

	/**
	 * @param bool $status
	 * @param null $userId
	 * @return mixed
	 */
	public function setNotificationsStatus($status, $userId = null) {
		$userId = $userId ?: $_SESSION['id_user'];
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			UPDATE users
			SET notifications= :status
			WHERE id= :user;"
		);
		$res = $stmt->execute(["user" => $userId, "status" => $status]);
		$this->disconnect();

		return $res;
	}

	public function getUsersIDs() {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT id, prenom
			FROM users"
		);
		$stmt->execute();
		$res = $stmt->fetchAll();
		$this->disconnect();

		return $res;
	}

	public function connexion($username, $password) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT id, username, prenom
			FROM users
			WHERE username = :username AND password = :password"
		);
		$stmt->execute(["username" => $username, "password" => md5($password)]);
		$res = $stmt->rowCount() ? $stmt->fetch() : null;
		$this->disconnect();

		return $res;
	}

	public function removeUsersArtist($idArtist) {
		$userId = $_SESSION['id_user'] ?? 0;
		$this->disableForeignKeysCheck();
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			DELETE FROM users_artists
			WHERE idArtist = :idArtist AND idUser = :idUser"
		);
		$res = $stmt->execute(["idArtist" => $idArtist, "idUser" => $userId]);
		$this->disconnect();
		$this->enableForeignKeysCheck();

		return $res;
	}

	public function getNotifiedUsers($force = false) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT id, prenom, mail
			FROM users".(!$force ? "
			WHERE notifications = TRUE" : "")
		);
		$stmt->execute();
		$res = $stmt->fetchAll();
		$this->disconnect();

		return $res;
	}

	public function getArtistIdFromSong($idSong) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT idArtist
			FROM artists_songs
			WHERE idAlbum = :idSong"
		);
		$stmt->execute(["idSong" => $idSong]);
		$res = $stmt->fetch();
		$this->disconnect();

		return $res ? $res['idArtist'] : null;
	}

	public function logCurlRequest($idArtist, $entity, $url, $data, $scrapped) {
		$date = new \DateTime();
		$datetime = $date->format('Y-m-d H:i:s');
		$sqlLog = "
			INSERT INTO logs_curl (idArtist, entity, url, data, scrapped, lastCall)
			VALUES (:idArtist, :entity, :url, :data, :scrapped, :lastCall)
			ON DUPLICATE KEY UPDATE
				idArtist = :idArtist,
				entity = :entity,
				url = :url,
				data = :data,
				scrapped = :scrapped,
				lastCall = :lastCall";

		$this->connect();
		$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $this->dbh->prepare($sqlLog);
		$resLog = $stmt->execute([
			'idArtist' => $idArtist,
			'entity' => $entity,
			'url' => $url,
			'data' => str_replace("'", "\'", $data),
			'scrapped' => $scrapped,
			'lastCall' => $datetime,
		]);
		$this->reinitAutoIncrement('logs_curl');
		$this->disconnect();

		return $resLog;
	}

	private function reinitAutoIncrement($table) {
		$this->connect();
		$stmt = $this->dbh->prepare("ALTER TABLE {$table} AUTO_INCREMENT = 0");
		$res = $stmt->execute();
		$this->disconnect();

		return $res;
	}

	private function enableForeignKeysCheck() {
		//SET FOREIGN_KEY_CHECKS = 1;
		return $this->foreignKeysCheck(true);
	}

	private function disableForeignKeysCheck() {
		//SET FOREIGN_KEY_CHECKS = 0;
		return $this->foreignKeysCheck(false);
	}

	private function foreignKeysCheck($mode = true) {
		$this->connect();
		$mode_str = $mode ? '1' : '0';
		// $stmt = $this->dbh->prepare("SET FOREIGN_KEY_CHECKS = :mode");
		$stmt = $this->dbh->prepare("SET FOREIGN_KEY_CHECKS = {$mode_str}");
		// $res = $stmt->execute(array("mode" => ($mode ? '1' : '0')));
		$res = $stmt->execute( /*array("mode" => $mode)*/);
		$this->disconnect();

		return $res;
	}

	public function getTokenByUsername($username) {
		$this->connect();
		$stmt = $this->dbh->prepare("
			SELECT *
			FROM user_auth
			WHERE username = :username
				AND is_expired = 0");
		$stmt->execute(["username" => $username]);
		if(!$stmt->rowCount()) {
			return false;
		}

		return $stmt->fetchAll();
	}

	public function setTokenAsExpired($id) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			UPDATE user_auth SET is_expired = 1 WHERE id = :id"
		);
		$res = $stmt->execute(["id" => $id]);
		$this->disconnect();

		return $res;
	}

	public function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			INSERT INTO user_auth (username, password_hash, selector_hash, expiry_date)
			VALUES (:username, :password_hash, :selector_hash, :expiry_date)"
		);
		$res = $stmt->execute([
			"username" => $username,
			"password_hash" => $random_password_hash,
			"selector_hash" => $random_selector_hash,
			"expiry_date" => $expiry_date,
		]);
		$this->disconnect();

		return $res;
	}

	public function getUserFromTokenId($token_id) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT u.*
			FROM user_auth ua
			INNER JOIN users u ON u.username = ua.username
			WHERE ua.id = :token_id"
		);
		$stmt->execute(["token_id" => $token_id]);
		if(!$stmt->rowCount()) {
			return false;
		}

		return $stmt->fetch();
		// $res = $stmt->fetch();
		// $this->disconnect();
		// return $res ?: false;
	}

	public function getUserFromUserToken($userToken = null) {
		$this->connect();
		$stmt = $this->dbh->prepare(
			"
			SELECT u.*
			FROM users u
			WHERE u.token = :token"
		);
		$stmt->execute(["token" => $userToken]);
		$res = $stmt->fetch();
		$this->disconnect();

		return $res ?: false;
	}

	public function disableAlbum($id, bool $removeSongs = true) {
		$this->connect();
		// $stmt = $this->dbh->prepare("UPDATE albums SET is_disabled = 1 WHERE id = :id");
		$stmt = $this->dbh->prepare("
			UPDATE albums
			SET date = NULL,
				custom = 1
			WHERE id = :id"
		);
		$res = $stmt->execute(["id" => $id]);
		$this->disconnect();

		if($removeSongs) {
			// Removing songs contained in the album
			$this->disableSong($id, true);
		}

		return $res;
	}

	public function disableSong($id, bool $isCollectionID = false) {
		$this->connect();
		// $stmt = $this->dbh->prepare("UPDATE albums SET is_disabled = 1 WHERE id = :id");
		$field = $isCollectionID ? "collectionId" : "id";
		$stmt = $this->dbh->prepare("
			UPDATE songs
			SET date = NULL,
				custom = 1
			WHERE {$field} = :id"
		);
		$res = $stmt->execute(["id" => $id]);
		$this->disconnect();

		return $res;
	}

	public function songExists($id): bool {
		$this->connect();
		$stmt = $this->dbh->prepare("
			SELECT id
			FROM songs
			WHERE id = :id"
		);
		$res = $stmt->execute(["id" => $id]);
		return (bool) $stmt->rowCount();
	}

	public function albumExists($id): bool {
		$this->connect();
		$stmt = $this->dbh->prepare("
			SELECT id
			FROM albums
			WHERE id = :id"
		);
		$res = $stmt->execute(["id" => $id]);

		return (bool) $stmt->rowCount();
	}

	// public function insertToken($id, $type)
	// {
	// 	$this->connect();
	// 	$stmt = $this->dbh->prepare(
	// 		"
	// 		INSERT INTO user_auth (username, password_hash, selector_hash, expiry_date)
	// 		VALUES (:username, :password_hash, :selector_hash, :expiry_date)"
	// 	);
	// 	$res = $stmt->execute(array(
	// 		"username" => $username,
	// 		"password_hash" => $random_password_hash,
	// 		"selector_hash" => $random_selector_hash,
	// 		"expiry_date" => $expiry_date,
	// 	));
	// 	$this->disconnect();
	// 	return $res;
	// }

	//	public function example()
	//	{
	//		$this->connect();
	//		$stmt = $this->dbh->query("SELECT * FROM albums");
	//		$this->disconnect();
	//
	//		$res = $stmt->fetchAll();
	//		return $res ? $this->setResults($res[0]) : null;
	//	}
}
