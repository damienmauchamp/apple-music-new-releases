<?php

$root_login = !empty($_POST['root_login']) ? $_POST['root_login'] : '';
$root_password = !empty($_POST['root_password']) ? $_POST['root_password'] : '';
$db_host = !empty($_POST['db_host']) ? $_POST['db_host'] : 'localhost';
$db_user = !empty($_POST['db_user']) ? $_POST['db_user'] : '';
$db_name = !empty($_POST['db_name']) ? $_POST['db_name'] : '';
$db_pwd = !empty($_POST['db_pwd']) ? $_POST['db_pwd'] : '';
$grant_global_access = !empty($_POST['grant_global_access']) ? (bool) $_POST['grant_global_access'] : false;


if ($root_login && $root_password && $db_host && $db_user && $db_pwd && $db_name) {

	$grant = $grant_global_access ? '*' : "`{$db_name}`";

	try {
		$dbh = new PDO("mysql:host={$db_host}", $root_login, $root_password);

		$ddb_creation = $dbh->exec("
			CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
			CREATE USER '{$db_user}'@'{$db_host}' IDENTIFIED BY '{$db_pwd}';
			GRANT ALL ON {$grant}.* TO '$db_user'@'{$db_host}';
			FLUSH PRIVILEGES;");

		$output = '';

		if ($ddb_creation) {

			// tables creation
			try {
				$dbh = new PDO("mysql:host={$db_host};port=3306;dbname={$db_name}", $db_user, $db_pwd);
				$dbh->exec('SET CHARACTER SET utf8');
				$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

				$tables_creation = "
CREATE TABLE `albums` (
  `id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `artistName` varchar(255) NOT NULL,
  `date` datetime DEFAULT NULL,
  `artwork` varchar(255) DEFAULT NULL,
  `explicit` tinyint(1) NOT NULL DEFAULT 0,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `artists` (
  `id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `artists_albums` (
  `id` int(11) NOT NULL,
  `idArtist` varchar(20) NOT NULL,
  `idAlbum` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `artists_songs` (
  `id` int(11) NOT NULL,
  `idArtist` varchar(255) NOT NULL,
  `idAlbum` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `logs_curl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idArtist` varchar(20) NOT NULL,
  `entity` varchar(20) NOT NULL,
  `url` varchar(255) NOT NULL,
  `data` longtext NOT NULL,
  `scrapped` tinyint(4) NOT NULL DEFAULT 0,
  `lastCall` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `songs` (
  `id` varchar(20) NOT NULL,
  `collectionId` varchar(20) DEFAULT NULL,
  `trackName` varchar(255) DEFAULT NULL,
  `artistName` varchar(255) DEFAULT NULL,
  `collectionName` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `artwork` varchar(255) DEFAULT NULL,
  `explicit` tinyint(1) DEFAULT 0,
  `isStreamable` tinyint(1) DEFAULT NULL,
  `added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `notifications` tinyint(1) NOT NULL DEFAULT 0,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users_artists` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idArtist` varchar(20) NOT NULL,
  `lastUpdate` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `albums_id_uindex` (`id`);

ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `artists_id_uindex` (`id`);

ALTER TABLE `artists_albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `artists_albums_id_uindex` (`id`),
  ADD UNIQUE KEY `UNIQUE_artist_album` (`idArtist`,`idAlbum`),
  ADD KEY `artists_albums_artists_id_fk` (`idArtist`),
  ADD KEY `artists_albums_albums_id_fk` (`idAlbum`);

ALTER TABLE `artists_songs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `artists_songs_id_uindex` (`id`),
  ADD UNIQUE KEY `UNIQUE_artist_song` (`idArtist`,`idAlbum`),
  ADD KEY `artists_songs_artists_id_fk` (`idArtist`),
  ADD KEY `artists_songs_songs_id_fk` (`idAlbum`);

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `logs_id_uindex` (`id`),
  ADD KEY `logs_users_id_fk` (`id_user`);

ALTER TABLE `logs_curl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idArtist` (`idArtist`,`entity`,`scrapped`);

ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users_artists`
  ADD KEY `id` (`id`);

ALTER TABLE `artists_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `artists_songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users_artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `logs_curl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `users` (username, password, mail, notifications, token)
VALUES ('{$db_user}', '{$db_pwd}', '', 0, '')";
 				$dbh->exec($tables_creation);

			} catch (PDOException $e) {
				echo "Erreur ! : " . $e->getMessage() . "<br/>";
				die("Connexion impossible à la base de données." .  $e->getMessage());
			}
			//

			$output .= "Database created.<br/>";

			// creating .env file
			$env = '
#db
DB_HOST="'.$db_host.'"
DB_NAME="'.$db_name.'"
DB_USERNAME="'.$db_user.'"
DB_PWD="'.$db_pwd.'"';

			file_put_contents(__DIR__ . '/.env', $env);

			$output .= ".env file created.<br/>";

			// removing installation script
			unlink(__DIR__ . '/install.php');

			$output .= "Installation script removed.<br/>";

			echo '<p>'.$output.'</p>';
			echo '<a href="." style="font-size: 200%">Let\'s get started !</a>';

			exit();

		}

	} catch (PDOException $e) {
		//die("DB ERROR: ". $e->getMessage());
		echo '<div class="error">An error occurred while creating the database. Please try again or check your inputs.</div>';
		// echo '<div class="error">'.print_r($dbh->errorInfo(), true).'.</div>';
		echo '<div class="error">'.$e->getMessage().'.</div><br/>';
	}
}
else if ($_POST) {
	echo '<div class="error">Please complete all fields.</div><br/>';
}
?>


<!DOCTYPE html>

<html>
<head>
	<title>Installation script</title>
</head>
<body>
	<form action="" method="POST" name="installation_script">
		<fieldset>
			<legend>Database access</legend>
			<p>Needed to create the database. These won't be saved.</p>

			<label for="root_login">Root login</label><br/>
			<input type="text" name="root_login" id="root_login" value="<?= $root_login ?>" require="required"/>
			<br/>
			<label for="root_password">Root password</label><br/>
			<input type="password" name="root_password" id="root_password" value="<?= $root_password ?>" require="required"/>
		</fieldset>

		<fieldset>
			<legend>Database information</legend>
			<p>Your login information to access the database. These will be persistent and will be in the .env file.</p>

			<label for="db_host">DB host</label><br/>
			<input type="text" name="db_host" id="db_host" value="<?= $db_host ?>" require="required"/>
			<br/>
			<label for="db_name">DB name</label><br/>
			<input type="text" name="db_name" id="db_name" value="<?= $db_name ?>" require="required"/>
			<br/>
			<label for="db_user">DB username</label><br/>
			<input type="text" name="db_user" id="db_user" value="<?= $db_user ?>" require="required"/>
			<br/>
			<label for="db_pwd">DB password</label><br/>
			<input type="password" name="db_pwd" id="db_pwd" value="<?= $db_pwd ?>" require="required"/>
			<br/>
			<input type="checkbox" name="grant_global_access" id="grant_global_access" value="1" <?= $grant_global_access ? 'checked="checked"' : '' ?> require="required"/>
			<label for="grant_global_access">Grant global user access</label><br/>
		</fieldset>

		<input type="submit" value="OK"/>
	</form>
</body>
</html>