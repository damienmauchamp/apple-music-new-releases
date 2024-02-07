<?php
// déconnexion
session_start();
session_unset();
session_destroy();

use AppleMusic\DB as db;

require __DIR__.'/vendor/autoload.php';
require_once "start.php";

//
$isLoggedIn = false;
require "login_auth.php";
if($isLoggedIn) {
	header("location: index.php");
}

// Get Current date, time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);

// Set Cookie expiration for 1 month
$cookie_days = 30;
$cookie_expiration_time = $current_time + ($cookie_days * 24 * 60 * 60);

$username = isset($_POST["username"]) ? addslashes($_POST["username"]) : null;
$password = isset($_POST["password"]) ? addslashes($_POST["password"]) : null;
$remember = $_POST["remember"] ?? false;
$submit = isset($_POST["submit"]) ? $_POST["submit"] : null;

if(file_exists(__DIR__.'/autolog.php')) {
	include __DIR__.'/autolog.php';
}

if($username && $password && $submit) {
	$isAuthenticated = false;

	$db = new db;
	if($res = $db->connexion($username, $password)) {
		$isAuthenticated = true;

		// Logged
		$_SESSION["id_user"] = $res["id"];
		$_SESSION["username"] = $res["username"];
		$_SESSION["prenom"] = $res["prenom"];

		// Cookies
		if($remember) {
			// USER
			setcookie("user_login", $res["username"], $cookie_expiration_time);

			// Password
			$random_password = getToken(16);
			setcookie("random_password", $random_password, $cookie_expiration_time);

			$random_selector = getToken(32);
			setcookie("random_selector", $random_selector, $cookie_expiration_time);

			$random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
			$random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);

			$expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);

			// mark existing token as expired
			$userTokens = $db->getTokenByUsername($username);
			foreach($userTokens ?? [] as $userToken) {
				// check cookie expiration by date
				if($userToken && $userToken["expiry_date"] < $current_date) {
					$db->setTokenAsExpired($userToken['id']);
				}
			}
			$db->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);

		}
		else {
			// clear cookies
			clearAuthCookie();
		}

		header("location: index.php");
	}
}

$root = './';

?>
<!DOCTYPE html>
<html>
	<head>
		<?php include "inc/meta.php"; ?>
		<style>
			#login {
				width: 100%;
				max-width: 500px;
				margin: 50px auto 0 auto;
				padding: 0 20px;
				text-align: center;
			}

			.tk-intro {
				font-size: 21px;
				line-height: 1.38105;
				font-weight: 400;
				letter-spacing: .011em;
				font-family: SF Pro Display, SF Pro Icons, Helvetica Neue, Helvetica, Arial, sans-serif;
				display: block;
				text-align: center;
			}

			.form-textbox {
				font-size: 17px;
				line-height: 1.29412;
				font-weight: 400;
				letter-spacing: -.021em;
				font-family: SF Pro Text, SF Pro Icons, Helvetica Neue, Helvetica, Arial, sans-serif;
				display: inline-block;
				box-sizing: border-box;
				vertical-align: top;
				width: 100%;
				height: 34px;
				margin-top: 0.8em;
				margin-bottom: 14px;
				padding-left: 15px;
				padding-right: 15px;
				color: #333;
				text-align: left;
				border: 1px solid #d6d6d6;
				border-radius: 4px;
				background: #fff;
				background-clip: padding-box;
			}

			#submit {
				font-size: 17px;
				line-height: 1.52947;
				font-weight: 400;
				letter-spacing: -.021em;
				font-family: "SF Pro Text", "Myriad Set Pro", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", "SF Pro Icons", "Apple Legacy Icons", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
				background-color: #0070c9;
				background: -webkit-linear-gradient(#42a1ec, #0070c9);
				background: linear-gradient(#42a1ec, #0070c9);
				border-color: #07c;
				border-width: 1px;
				border-style: solid;
				border-radius: 4px;
				color: #fff;
				cursor: pointer;
				display: inline-block;
				min-width: 30px;
				padding-left: 15px;
				padding-right: 15px;
				padding-top: 3px;
				padding-bottom: 4px;
				text-align: center;
				white-space: nowrap;

				/*  */
				display: block;
				margin: auto;
			}

			#submit:hover {
				background-color: #147bcd;
				background: -webkit-linear-gradient(#51a9ee, #147bcd);
				background: linear-gradient(#51a9ee, #147bcd);
				border-color: #1482d0;
				text-decoration: none;
			}

			#submit:active {
				background-color: #0067b9;
				background: -webkit-linear-gradient(#3d94d9, #0067b9);
				background: linear-gradient(#3d94d9, #0067b9);
				border-color: #006dbc;
				outline: none;
			}

			label.for-checkbox {
				display: inline-block;
				margin-bottom: 14px;
				font-size: 18px;
			}
		</style>
	</head>
	<body>
		<form id="login" method="post" action="login.php">
			<label class="si-container-title tk-intro" for="username">Nom d'utilisateur</label>
			<input type="text" class="form-textbox form-textbox-text" name="username" id="username" value="<?= $_COOKIE["user_login"] ?? '' ?>">

			<label class="si-container-title tk-intro" for="password">Mot de passe</label>
			<input type="password" class="form-textbox form-textbox-text" name="password" id="password" value="<?= $_COOKIE["user_pwd"] ?? '' ?>">

			<input type="checkbox" name="remember" id="remember" class="form-checkbox" <?= (isset($_COOKIE["user_login"]) || true) ? 'checked="checked"' : '' ?> control-id="ControlID-4">
			<label class="si-container-title tk-intro for-checkbox" for="remember">Remember</label>

			<input type="submit" id="submit" name="submit" value="Se connecter">
		</form>
	</body>
</html>
