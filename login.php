<?php
// déconnexion
session_start();
session_unset();
session_destroy();

use AppleMusic\DB as db;

require __DIR__ . '/vendor/autoload.php';
require_once "start.php";

$username = isset($_POST["username"]) ? addslashes($_POST["username"]) : null;
$password = isset($_POST["password"]) ? addslashes($_POST["password"]) : null;
$submit = isset($_POST["submit"]) ? $_POST["submit"] : null;

if (file_exists(__DIR__ . '/autolog.php'))
    include __DIR__ . '/autolog.php';

if ($username && $password && $submit) {
    $db = new db;
    if ($res = $db->connexion($username, $password)) {
        $_SESSION["id_user"] = $res["id"];
        $_SESSION["username"] = $res["username"];
        $_SESSION["prenom"] = $res["prenom"];

        header("location: index.php");
    }
}

?>

<form id="login" method="post" action="login.php">
    <label for="username">Nom d'utilisateur</label>
    <input type="text" name="username" id="username"/>

    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password"/>

    <input type="submit" id="submit" name="submit" value="Se connecter"/>
</form>
