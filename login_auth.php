<?php

use AppleMusic\DB as db;

/* COOKIES */
// Get Current date, time
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);

// Set Cookie expiration for 1 month
$cookie_days = 30;
$cookie_expiration_time = $current_time + ($cookie_days * 24 * 60 * 60);

//
if (id_user() > 0) {
	$isLoggedIn = true;
	$_SESSION['id_user'] = id_user();
}
// Check if loggedin session exists
else if (!empty($_COOKIE["user_login"]) &&
	!empty($_COOKIE["random_password"]) &&
	!empty($_COOKIE["random_selector"])) {

	$db = new db();

	// Initiate auth token verification diirective to false
	$isPasswordVerified = false;
	$isSelectorVerified = false;
	$isExpiryDateVerified = false;

	// Get token for username
	$userTokens = $db->getTokenByUsername($_COOKIE["user_login"]);

	foreach ($userTokens as $userToken) {

		// Validate random password cookie with database
		if (password_verify($_COOKIE["random_password"], $userToken["password_hash"])) {
			$isPasswordVerified = true;
		}

		// Validate random selector cookie with database
		if (password_verify($_COOKIE["random_selector"], $userToken["selector_hash"])) {
			$isSelectorVerified = true;
		}

		// check cookie expiration by date
		if ($userToken["expiry_date"] >= $current_date) {
			$isExpiryDateVerified = true;
		}

		// Redirect if all cookie based validation retuens true
		// Else, mark the token as expired and clear cookies
		if (!empty($userToken["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDateVerified) {
			$userInfo = $db->getUserFromTokenId($userToken['id']);
			if ($userInfo) {
				$isLoggedIn = true;
				$_SESSION["id_user"] = (int) $userInfo['id'];
				// $_SESSION["id_user"] = $db->getUserFromTokenId($userToken['id']);
			}
		}

		if (!$isLoggedIn) {
			// mark existing token as expired
			if ($userToken['id'] && !$isExpiryDateVerified) {
				$db->setTokenAsExpired($userToken['id']);
			}
		} else {
			break;
		}
	}

	if (!$isLoggedIn) {
		// clear cookies
		clearAuthCookie();
	}
}
