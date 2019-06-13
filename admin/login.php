<?php
session_start();
include_once '../Candide.php';
include_once 'php/login/login.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candide - Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/main.min.css">
</head>
<body id="login">

<form action="login.php" method="post">
    <h1>Connexion</h1>
    <div class="inputContainer">
        <h2>Identifiant :</h2>
        <input type="text" name="identifier" value="<?php echo key_exists("identifier",$_POST) ? $_POST["identifier"] : "" ?>">
        <p class="error"><?php echo (array_key_exists('identifier',$errors) && $errors['identifier'] != "ok") ? $errors['identifier'] : "" ?></p>
    </div>
    <div class="inputContainer">
        <h2>Mot de passe :</h2>
        <input type="password" name="password" value="<?php echo key_exists("password",$_POST) ? $_POST["password"] : "" ?>">
        <p class="error"><?php echo (array_key_exists('password',$errors) && $errors['password'] != "ok") ? $errors['password'] : "" ?></p>
    </div>
    <div class="submitContainer clickable">
        <input type="submit" value="Se connecter">
    </div>
</form>

<script src="src/js/main.js"></script>
</body>
</html>