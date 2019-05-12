<?php
session_start();
include_once '../Candide.php';

const KEY_USER = PROJECT_NAME."_user";
const KEY_PASSWORD = PROJECT_NAME."_password";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candide - Connexion</title>

    <link rel="stylesheet" href="assets/styles/main.min.css">
</head>
<body id="login">

<form action="login.php" method="post">
    <h1>Connexion</h1>
    <div class="inputContainer">
        <h2>Identifiant :</h2>
        <textarea name='identifier'></textarea>
    </div>
    <div class="inputContainer">
        <h2>Mot de passe :</h2>
        <textarea name="password"></textarea>
    </div>
    <div class="submitContainer clickable">
        <input type="submit" value="Se connecter">
    </div>
</form>

<script src="src/js/main.js"></script>
</body>
</html>