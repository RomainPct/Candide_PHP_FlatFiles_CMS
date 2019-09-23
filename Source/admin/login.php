<?php
session_start();
include 'CandideAdmin.php';
$texts = new AdminTextsManager("login");
if (key_exists("logout",$_GET)) {
    Authentication::logout();
} else if ( key_exists(PROJECT_NAME."_logedin",$_SESSION)) {
    header("Location: ../admin/");
}
$errors = Authentication::login($_POST,$texts);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candide - <?php $texts->echo("title") ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/styles/main.min.css">
</head>
<body id="login">

<form action="login.php" method="post">
    <h1><?php $texts->echo("title") ?></h1>
    <div class="inputContainer">
        <h2><?php $texts->echo("identifier") ?> :</h2>
        <input type="text" name="identifier" value="<?php echo key_exists("identifier",$_POST) ? $_POST["identifier"] : "" ?>">
        <p class="error"><?php echo (array_key_exists('identifier',$errors) && $errors['identifier'] != "ok") ? $errors['identifier'] : "" ?></p>
    </div>
    <div class="inputContainer">
        <h2><?php $texts->echo("password") ?> :</h2>
        <input type="password" name="password" value="<?php echo key_exists("password",$_POST) ? $_POST["password"] : "" ?>">
        <p class="error"><?php echo (array_key_exists('password',$errors) && $errors['password'] != "ok") ? $errors['password'] : "" ?></p>
    </div>
    <div class="submitContainer clickable">
        <input type="submit" value="<?php $texts->echo("action") ?>">
    </div>
</form>

<script src="src/js/main.js"></script>
</body>
</html>