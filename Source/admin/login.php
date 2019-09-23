<?php
session_start();
include 'CandideAdmin.php';
$texts = new AdminTextsManager("login");
$errors = [];
if (key_exists("logout",$_GET)) {
    unset($_SESSION[PROJECT_NAME."_logedin"]);
} else if ( key_exists(PROJECT_NAME."_logedin",$_SESSION)) {
    $authorized = false;
    foreach (ADMINISTRATORS as $user) {
        if ($_SESSION[PROJECT_NAME."_logedin"] == hash("sha256",$user[0]).hash("sha256",$user[1])) {
            $authorized = true;
        }
    }
    if ($authorized) {
        header("Location: index.php");
    } else {
        unset($_SESSION[PROJECT_NAME."_logedin"]);
    }
} else if ( !empty($_POST["identifier"])) {
    foreach (ADMINISTRATORS as $user) {
        if ($user[0] == $_POST["identifier"]) {
            if (strtoupper($user[1]) == strtoupper(hash("sha256",$_POST["password"]))) {
                $_SESSION[PROJECT_NAME."_logedin"] = hash("sha256",$user[0]).hash("sha256",$user[1]);
                header("Location: index.php");
            } else {
                $errors["password"] = $texts->get("error_password");
                $errors["identifier"] = "ok";
            }
        } else {
            $errors["identifier"] = (array_key_exists("identifier",$errors) && $errors["identifier"] == "ok") ? "ok" : $texts->get("error_identifier");
        }
    }
}
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