<?php
require __DIR__.'/vendor/autoload.php';
require_once "start.php";

if($debug ?? false) {
	$rustart = getrusage();
	$time_start = microtime(true);
}

// echo '<pre>' . print_r([
// 	'_SESSION' => $_SESSION,
// 	'_COOKIE' => $_COOKIE,
// 	'idUser' => $_SESSION['id_user'] ?? 0,
// ], true) . '</pre>';
checkConnexion();
// echo '<pre>' . print_r([
// 	'_SESSION' => $_SESSION,
// 	'_COOKIE' => $_COOKIE,
// 	'idUser' => $_SESSION['id_user'] ?? 0,
// ], true) . '</pre>';
$root = "";
global $news;

if(isset($_POST["load_songs"]) && $_POST["load_songs"]) {

	$explicit_only = true;
	$filtrer_albums = isset($_POST["filtrer"]) && $_POST["filtrer"];
	// type === 1 : only streamable
	// type === 2 : only not streamable/upcoming
	$type = isset($_POST["type"]) ? (int) $_POST["type"] : null;
	$available = isset($_POST["available"]) && $_POST["available"];
	$compilation = $_POST["compilation"] ?? null;
	if($compilation === 'false') {
		$compilation = false;
	}

	header("Content-type:text/html");
	// displaySongs(getAllSongs($filtrer_albums, $explicit_only, $type, $available));
	displaySongsNew(getAllSongs($filtrer_albums, $explicit_only, $type, $available, $compilation));
	exit;
}

// reduce lastUpdated by a week
if($delay) {
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

if($news && $nodisplay) {
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
			<?php include "inc/meta.php"; ?>
		</head>
		<body class="<?= $theme ?>">
			<div class="main">
				<?php include "inc/nav.php"; ?>

				<section class="main-header l-content-width section" style="border-top:none">
					<h1 class="section__headline--hero"><?= $news ? "Mise à jour" : "Nouvelles Sorties" ?></h1>
					<i class="fa fa-cog" id="settings"></i>
					<!-- <div id="maj-cont">Dernière MAJ : <?= getLastRefresh(); ?></div> -->
					<!-- 	<div id="mail-alert-cont">
				<label for="mail-alert">
					<input type="checkbox"
						   id="mail-alert" <?= getNotificationsStatus() ? "checked=\"checked\"" : "" ?>/>
					Notifications par mail</label>
			</div> -->
				</section>

				<?php if($news): ?>

					<section class="l-content-width section section--bordered">
						<h2 class="section__headline">
							Nouveaux albums
						</h2>

						<div class="l-row" id="new-albums">

							<?php if(!$full):
								logRefresh(); ?>
								<script>getNewReleases(<?=$scrapped ? 'true' : ''?>);</script>
								<div class="spinner-cont">
									<div id="loading-spinner"
										class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
								</div>
							<?php else:
								logRefresh("full");
								$res = $scrapped ? getAllNewScrappedReleases() : getAllNewReleases();
								$albums = $res["albums"];
								$songs = $res["songs"];
							endif ?>

						</div>

					</section>

				<?php else:
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
					$songs = [];  //false;//getAllSongs();
					//var_dump($songs);
					?>
					<!-- RECENT SONGS -->
					<section class="l-content-width section section--bordered">
						<div class="l-row">
							<div class="l-column small-12">
								<h2 class="section__headline">
									Chansons récentes
								</h2>
								<!-- <table class="table table--see-all" id="song-table-table"> -->
								<div class="table table--see-all" id="song-table-table">
									<!-- <thead class="table__head">
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
									</thead> -->
									<div id="recent-songs-table-tbody">
										<!-- <tbody id="recent-songs-table-tbody"> -->
										<?php //displaySongs($songs)
										?>
										<!-- </tbody> -->
									</div>
								</div>
								<!-- </table> -->
								<div class="spinner-cont">
									<div id="loading-spinner_recent-songs"
										class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
								</div>
							</div>
						</div>
					</section>

					<!-- RECENT SONGS FROM COMPILATIONS -->
					<section class="l-content-width section section--bordered">
						<div class="l-row">
							<div class="l-column small-12">
								<h2 class="section__headline">
									Chansons récentes (compilations)
								</h2>
								<!-- <table class="table table--see-all" id="song-table-table"> -->
								<div class="table table--see-all" id="song-table-table">
									<!-- <thead class="table__head">
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
									</thead> -->
									<div id="recent-compilation-songs-table-tbody">
										<!-- <tbody id="recent-songs-table-tbody"> -->
										<?php //displaySongs($songs)
										?>
										<!-- </tbody> -->
									</div>
								</div>
								<!-- </table> -->
								<div class="spinner-cont">
									<div id="loading-spinner_recent-compilation-songs"
										class="we-loading-spinner we-loading-spinner--see-all ember-view"></div>
								</div>
							</div>
						</div>
					</section>

					<!-- UPCOMING SONGS BUT AVAILABLE -->
					<section class="l-content-width section section--bordered">
						<div class="l-row">
							<div class="l-column small-12">
								<h2 class="section__headline">
									Chansons disponibles à venir
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
									<tbody id="upcoming-streamable-songs-table-tbody">
										<?php //displaySongs($songs)
										?>
									</tbody>
								</table>
								<div class="spinner-cont">
									<div id="loading-spinner_upcoming-streamable-songs"
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
										<?php //displaySongs($songs)
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

					<?php // Chansons à venir
					?>

					<?php // songs start
					if($songs): ?>
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
											<?php displaySongs($songs) ?>
										</tbody>
									</table>
								</div>
							</div>
						</section>
					<?php // songs end
					endif; ?>

					<?php // albums start
					if($albums): ?>
						<section class="artist l-content-width section section--bordered">
							<h2 class="section__headline">
								Tous les albums
							</h2>
							<div class="l-row">
								<?php displayAlbums($albums) ?>
							</div>
						</section>
					<?php // albums end
					endif; ?>

				<?php endif; ?>
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
							type: 1,
							compilation: false,
						},
						success: function (data) {
							$("#recent-songs-table-tbody").empty();
							console.log('songs loaded.')
							if (!data) {
								$("#recent-songs-table-tbody").closest('section').hide();
							} else {
								$("#recent-songs-table-tbody").append(data);
							}
							$("#loading-spinner_recent-songs").hide();
						}
					});
				}();
				var load_compilation_songs = function () {
					console.log('loading compilation songs...')
					$.ajax({
						url: "index.php",
						method: "POST",
						data: {
							load_songs: true,
							filtrer: true,
							type: 1,
							compilation: true,
						},
						success: function (data) {
							$("#recent-compilation-songs-table-tbody").empty();
							console.log('songs loaded.')
							if (!data) {
								$("#recent-compilation-songs-table-tbody").closest('section').hide();
							} else {
								$("#recent-compilation-songs-table-tbody").append(data);
							}
							$("#loading-spinner_recent-compilation-songs").hide();
						}
					});
				}();
				var load_upcoming_available_songs = function () {
					console.log('loading upcoming but available songs...')
					$.ajax({
						url: "index.php",
						method: "POST",
						data: {
							load_songs: true,
							filtrer: true,
							type: 2,
							available: 1
						},
						success: function (data) {
							console.log('songs loaded.')
							if (!data) {
								$("#upcoming-streamable-songs-table-tbody").closest('section').hide();
							} else {
								$("#upcoming-streamable-songs-table-tbody").append(data);
							}
							$("#loading-spinner_upcoming-streamable-songs").hide();
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
							type: 2,
							available: 0
						},
						success: function (data) {
							console.log('songs loaded.')
							if (!data) {
								$("#upcoming-songs-table-tbody").closest('section').hide();
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
				<li class="remove" data-action="remove-item"><a href="#">Ne plus afficher</a></li>
			</ul>


			<script>
			</script>
		</body>
	</html>
<?php
if($debug):
	function rutime($ru, $rus, $index) {
		return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
			- ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
	}

	$ru = getrusage();
	echo "This process used ".rutime($ru, $rustart, "utime").
		" ms for its computations\n";
	echo "It spent ".rutime($ru, $rustart, "stime").
		" ms in system calls\n";
	$time_end = microtime(true);
	//dividing with 60 will give the execution time in minutes otherwise seconds
	$execution_time = ($time_end - $time_start) / 60;
	//execution time of the script
	echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
endif;