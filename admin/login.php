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
<body>

<form action="login.php" method="post">
    <div class="inputContainer">
        <label for="identifier" id="identifier">Identifiant :</label>
        <textarea name='identifier'></textarea>
    </div>
    <div class="inputContainer">
        <label for="password" id="password">Mot de passe :</label>
        <textarea name="password"></textarea>
    </div>
    <div class="submitContainer clickable">
        <input type="submit" value="Se connecter">
    </div>
</form>

<script src="src/js/main.js"></script>
</body>
</html>