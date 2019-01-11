<?php

require __DIR__ . '/vendor/autoload.php';
require_once "start.php";

use PHPMailer\PHPMailer\PHPMailer;
use AppleMusic\DB as db;

$db = new db();
global $daysInterval;

header("Content-Type: application/json");

// récup les nom/prénom/mail/id des users
$users = $db->getNotifiedUsers();

$env = explode(":", file_get_contents(__DIR__ . '/.env'));
$mail_nom = $env[3] ? $env[3] : null;
$mail_psw = $env[4] ? $env[4] : null;

foreach ($users as $user) {
    $user_id = $user["id"];
    $user_name = $user["prenom"];
    $user_email = $user["mail"];

    $json_albums = $db->getUserAlbums($user_id);
    $json_songs = $db->getUserSongs($daysInterval, $user_id);

//    echo $json_songs;exit;

    $albums = $songs = "";
    foreach (json_decode($json_albums) as $item) {
        $albums .= "<li><a href='//itunes.apple.com/fr/album/" . $item->id . "' about='blank'>" . $item->name . "</a> par " . $item->artistName . " (" . date("d/m", strtotime($item->date)) . ")</li>";
    }
    foreach (json_decode($json_songs) as $item) {
        $songs .= "<li><a href='//itunes.apple.com/fr/album/" . $item->collectionId . "?i=" . $item->id . "' about='blank'>" . $item->trackName . "</a> par " . $item->artistName . " (" . date("d/m", strtotime($item->date)) . ")</li>";
    }

    $body = "<h3>Albums</h3><ul>$albums</ul><h3>Morceaux</h3><ul>$songs</ul>";

    $mail = new PHPMailer;

    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
//$mail->SMTPDebug = 2;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        $mail->Username = $mail_nom;
        $mail->Password = $mail_psw;

        $mail->setFrom($mail_nom);
        $mail->FromName = 'Apple Music - Update';

        $mail->addAddress($user_email, $user_name); // app user who's received all the news

        $mail->addReplyTo('noreply', 'noreply');

        $mail->WordWrap = 50;
        $mail->isHTML(true);

        $mail->Subject = "Nouveautés du vendredi " . date("d/m/Y");
        $mail->Body = $body;

        $mail->send();

        echo json_encode(array("response" => true, "data" => array()));

    } catch (Exception $e) {
        echo json_encode(array("response" => false, "error" => "Message could not be sent. Mailer Error: ', $mail->ErrorInfo"));
    }
}