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
$tmp_id_user = isset($_SESSION["id_user"]) && $_SESSION["id_user"] > 0 ? $_SESSION["id_user"] : null;
if (is_array($tmp_id_user) && isset($tmp_id_user['id']) && $tmp_id_user['id'] > 0) {
    $tmp_id_user = $tmp_id_user['id'];
} else if (is_array($tmp_id_user) && 
    isset($tmp_id_user[0]) && 
    isset($tmp_id_user[0]['id'])) {
    $tmp_id_user = $tmp_id_user[0]['id'];
}

//
if (!empty($tmp_id_user) && $tmp_id_user > 0) {
    $isLoggedIn = true;
    $idUser = $tmp_id_user;
}
// Check if loggedin session exists
else if (!empty($_COOKIE["user_login"]) && 
    !empty($_COOKIE["random_password"]) && 
    !empty($_COOKIE["random_selector"])) {

    $db = new db;

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
        if($userToken["expiry_date"] >= $current_date) {
            $isExpiryDareVerified = true;
        }
        
        // Redirect if all cookie based validation retuens true
        // Else, mark the token as expired and clear cookies
        if (!empty($userToken["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDareVerified) {
            $userInfo = $db->getUserFromTokenId($userToken['id']);
            if ($userInfo) {
                $isLoggedIn = true;
                $_SESSION["id_user"] = $db->getUserFromTokenId($userToken['id']);

                $idUser = isset($_SESSION["id_user"]) ? $_SESSION["id_user"] : -1;
                if (is_array($idUser) && isset($idUser['id'])) {
                    $idUser = $idUser['id'];
                } else if (is_array($idUser) && 
                    isset($idUser[0]) && 
                    isset($idUser[0]['id'])) {
                    $idUser = $idUser[0]['id'];
                }
            }
        }

        if (!$isLoggedIn) {
            // mark existing token as expired
            if ($userToken['id'] && !$isExpiryDareVerified) {
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