<?php
require __DIR__ . '/vendor/autoload.php';
require_once "start.php";

if ($debug) :
	$rustart = getrusage();
	$time_start = microtime(true);
endif;

checkConnexion();
$root = "";
global $news;

if (isset($_POST["load_songs"]) && $_POST["load_songs"]) {

	$explicit_only = true;
	$filtrer_albums = isset($_POST["filtrer"]) && $_POST["filtrer"];
	// type === 1 : only streamable
	// type === 2 : only not streamable/upcoming
	$type = isset($_POST["type"]) ? (int) $_POST["type"] : null;

	header("Content-type:text/html");
	displaySongs(getAllSongs($filtrer_albums, $explicit_only, $type));
	exit;
}

// reduce lastUpdated by a week
if ($delay) {
	editLastUpdated($delay);
}


/**
 * TODO : page de logs
 *
 * artists that needs update
 * var needToUpdateIds = [];
 * $("#new-albums .album").each(function() {
 * needToUpdateIds.push($(this).data("amArtistId"));
 * });
 * needToUpdateIds.forEach(function(x) {
 * console.log("'" + x + "', ");
 * });
 *
 * UPDATE users_artists
 * SET lastUpdate = NOW()
 * WHERE idUser = 1 AND idArtist IN
 */

if ($news && $nodisplay) {
	logRefresh("no display");
	$res = getAllNewReleases();
	$albums = $res["albums"];
	$songs = $res["songs"];
	echo json_encode(true);
	exit;
}

?>
	<!DOCTYPE html>
	<html>
	<head>
		<? include "inc/meta.php"; ?>
	</head>
	<body class="<?= $theme ?>">
	<div class="main">
		<? include "inc/nav.php"; ?>

		<section class="main-header l-content-width section" style="border-top:none">
			<h1 class="section__headline--hero"><?= $news ? "Mise à jour" : "Nouvelles Sorties" ?></h1>
			<i class="fa fa-cog" id="settings" style="position: relative;top: -30px;"></i>
			<!-- <div id="maj-cont">Dernière MAJ : <?= getLastRefresh(); ?></div> -->
		<!-- 	<div id="mail-alert-cont">
				<label for="mail-alert">
					<input type="checkbox"
						   id="mail-alert" <?= getNotificationsStatus() ? "checked=\"checked\"" : "" ?>/>
					Notifications par mail</label>
			</div> -->
		</section>

		<? if ($news) : ?>

			<section class="l-content-width section section--bordered">
				<h2 class="section__headline">
					Nouveaux albums
				</h2>

				<div class="l-row" id="new-albums">

					<? if (!$full) :
						logRefresh(); ?>
						<script>getNewReleases(<?= $scrapped ? 'true' : '' ?>);</script>
						<div class="spinner-cont">
							<div id="loading-spinner"
								 class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
						</div>
					<? else :
						logRefresh("full");
						$res = $scrapped ? getAllNewScrappedReleases() : getAllNewReleases();
						$albums = $res["albums"];
						$songs = $res["songs"];
					endif ?>

				</div>

			</section>

		<? else :
			// Weekly releases : Sorties de la semaine
			$display = 'grid';
			echo getThisWeekReleases('albums');
			echo getThisWeekReleases('singles');

			// Upcoming Releases
			$display = 'row';
			echo getUpcomingReleases();

			//exit();

			// Latest releases by artists
			$display = 'row';
			$albums = []; //getAllAlbums();
			$songs = []; //false;//getAllSongs();
			//var_dump($songs);
			?>
			<!-- RECENT SONGS -->
			<section class="l-content-width section section--bordered">
				<div class="l-row">
					<div class="l-column small-12">
						<h2 class="section__headline">
							Chansons récentes
						</h2>
						<table class="table table--see-all" id="song-table-table">
							<thead class="table__head">
							<tr>
								<th class="table__head__heading--artwork"></th>
								<th class="table__head__heading table__head__heading--song">TITRE</th>
								<th class="table__head__heading table__head__heading--artist small-hide large-show-tablecell">
									ARTISTE
								</th>
								<th class="table__head__heading table__head__heading--album small-hide medium-show-tablecell">
									ALBUM
								</th>
								<th class="table__head__heading table__head__heading--duration">SORTIE</th>
							</tr>
							</thead>
							<tbody id="recent-songs-table-tbody">
							<? //displaySongs($songs)
							?>
							</tbody>
						</table>
						<div class="spinner-cont">
							<div id="loading-spinner_recent-songs"
								 class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
						</div>
					</div>
				</div>
			</section>

			<!-- UPCOMING SONGS -->
			<section class="l-content-width section section--bordered">
				<div class="l-row">
					<div class="l-column small-12">
						<h2 class="section__headline">
							Chansons à venir
						</h2>
						<table class="table table--see-all" id="song-table-table">
							<thead class="table__head">
							<tr>
								<th class="table__head__heading--artwork"></th>
								<th class="table__head__heading table__head__heading--song">TITRE</th>
								<th class="table__head__heading table__head__heading--artist small-hide large-show-tablecell">
									ARTISTE
								</th>
								<th class="table__head__heading table__head__heading--album small-hide medium-show-tablecell">
									ALBUM
								</th>
								<th class="table__head__heading table__head__heading--duration">SORTIE</th>
							</tr>
							</thead>
							<tbody id="upcoming-songs-table-tbody">
							<? //displaySongs($songs)
							?>
							</tbody>
						</table>
						<div class="spinner-cont">
							<div id="loading-spinner_upcoming-songs"
								 class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
						</div>
					</div>
				</div>
			</section>

			<?
			// Chansons à venir
			?>

			<? // songs start
			if ($songs) : ?>
				<section class="l-content-width section section--bordered">
					<div class="l-row">
						<div class="l-column small-12">
							<h2 class="section__headline">
								Toutes les chansons
							</h2>
							<table class="table table--see-all">
								<thead class="table__head">
								<tr>
									<th class="table__head__heading--artwork"></th>
									<th class="table__head__heading table__head__heading--song">TITRE</th>
									<th class="table__head__heading table__head__heading--artist small-hide large-show-tablecell">
										ARTISTE
									</th>
									<th class="table__head__heading table__head__heading--album small-hide medium-show-tablecell">
										ALBUM
									</th>
									<th class="table__head__heading table__head__heading--duration">SORTIE</th>
								</tr>
								</thead>
								<tbody>
								<? displaySongs($songs) ?>
								</tbody>
							</table>
						</div>
					</div>
				</section>
			<? // songs end
			endif; ?>

			<? // albums start
			if ($albums) : ?>
				<section class="artist l-content-width section section--bordered">
					<h2 class="section__headline">
						Tous les albums
					</h2>
					<div class="l-row">
						<? displayAlbums($albums) ?>
					</div>
				</section>
			<? // albums end
			endif; ?>

		<? endif; ?>
	</div>

	<script>
		var load_songs = function () {
			console.log('loading songs...')
			$.ajax({
				url: "index.php",
				method: "POST",
				data: {
					load_songs: true,
					filtrer: true,
					type: 1
				},
				success: function(data) {
					console.log('songs loaded.')
					if (!data) {
						$("#recent-songs-table-tbody").hide();
					} else {
						$("#recent-songs-table-tbody").append(data);
					}
					$("#loading-spinner_recent-songs").hide();
				}
			});
		}();
		var load_upcoming_songs = function () {
			console.log('loading upcoming songs...')
			$.ajax({
				url: "index.php",
				method: "POST",
				data: {
					load_songs: true,
					filtrer: true,
					type: 2
				},
				success: function(data) {
					console.log('songs loaded.')
					if (!data) {
						$("#upcoming-songs-table-tbody").hide();
					} else {
						$("#upcoming-songs-table-tbody").append(data);
					}
					$("#loading-spinner_upcoming-songs").hide();
				}
			});
		}();
	</script>

	<ul class='custom-menu'>
		<li data-action="open-itunes"><a href="#">Afficher sur iTunes</a></li>
		<li data-action="open-browser"><a href="#" target="_blank">Afficher dans le navigateur</a></li>
	</ul>


	<script>
		// console.log('yo');

		// console.log('user_login:', getCookie('user_login'));
		// console.log('random_password:', getCookie('random_password'));
		// console.log('random_selector:', getCookie('random_selector'));
		
		// setCookie('user_login', getCookie('user_login'))
		// setCookie('random_password', getCookie('random_password'))
		// setCookie('random_selector', getCookie('random_selector'))
		// console.log(getCookie())
            // [redirect] => http://damien.local/applemusic-update/index.php
            // [user_login] => damien
            // [random_password] => m48QqO6tCEsq1Xn8
            // [random_selector] => wkgKbcBfWgvqxJ3F4TdBcLVam0F8tZbv
            // [theme] => variant
            // [PHPSESSID] => 6jpknrf6qmerogbq4d41fgdmb6

	</script>
	</body>
	</html>
<?
if ($debug) :
	function rutime($ru, $rus, $index)
	{
		return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
			- ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
	}

	$ru = getrusage();
	echo "This process used " . rutime($ru, $rustart, "utime") .
		" ms for its computations\n";
	echo "It spent " . rutime($ru, $rustart, "stime") .
		" ms in system calls\n";
	$time_end = microtime(true);
	//dividing with 60 will give the execution time in minutes otherwise seconds
	$execution_time = ($time_end - $time_start) / 60;
	//execution time of the script
	echo '<b>Total Execution Time:</b> ' . $execution_time . ' Mins';
endif;