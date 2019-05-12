<?php
session_start();
include_once '../Candide.php';

if ( !empty($_POST["identifier"]) && !empty($_POST["password"]) ) {
    foreach (ADMINISTRATORS as $user) {
        if ($user[0] == $_POST["identifier"]) {
            if (strtoupper($user[1]) == strtoupper(hash("sha256",$_POST["password"]))) {
                $_SESSION[PROJECT_NAME."_logedin"] = hash("sha256",$user[0]).hash("sha256",$user[1]);
                header("Location: index.php");
            } else {
                echo "Mot de passe incorrect";
            }
        } else {
            echo "Cet identifiant n'existe pas";
        }
    }
}

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
        <textarea name='identifier'><?php echo key_exists("identifier",$_POST) ? $_POST["identifier"] : "" ?></textarea>
    </div>
    <div class="inputContainer">
        <h2>Mot de passe :</h2>
        <textarea name="password"><?php echo key_exists("password",$_POST) ? $_POST["password"] : "" ?></textarea>
    </div>
    <div class="submitContainer clickable">
        <input type="submit" value="Se connecter">
    </div>
</form>

<script src="src/js/main.js"></script>
</body>
</html>