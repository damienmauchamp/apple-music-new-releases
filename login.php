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

$root = './';

?>
<!DOCTYPE html>
<html>
<head>
    <? include "inc/meta.php"; ?>
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
        font-family: SF Pro Display,SF Pro Icons,Helvetica Neue,Helvetica,Arial,sans-serif;
        display: block;
        text-align: center;
    }
    .form-textbox {
        font-size: 17px;
        line-height: 1.29412;
        font-weight: 400;
        letter-spacing: -.021em;
        font-family: SF Pro Text,SF Pro Icons,Helvetica Neue,Helvetica,Arial,sans-serif;
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
        font-family: "SF Pro Text","Myriad Set Pro",system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI","SF Pro Icons","Apple Legacy Icons","Helvetica Neue","Helvetica","Arial",sans-serif;
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
</style>
</head>
<body>
    <form id="login" method="post" action="login.php">
        <label class="si-container-title tk-intro" for="username">Nom d'utilisateur</label>
        <input type="text"  class="form-textbox form-textbox-text" name="username" id="username">

        <label class="si-container-title tk-intro" for="password">Mot de passe</label>
        <input type="password" class="form-textbox form-textbox-text" name="password" id="password">

        <input type="submit" id="submit" name="submit" value="Se connecter">
    </form>
</body>
</html>
