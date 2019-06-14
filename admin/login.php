<?php
session_start();
include_once '../Candide.php';
$errors = [];
if (key_exists("logout",$_GET)) {
    unset($_SESSION[PROJECT_NAME."_logedin"]);
} else if ( key_exists(PROJECT_NAME."_logedin",$_SESSION)) {
    $authorized = false;
    foreach (ADMINISTRATORS as $user) {
        if ($_SESSION[PROJECT_NAME."_logedin"] == $_SESSION[PROJECT_NAME."_candideRecovery"]) {
            $authorized = true;
        }
    }
    if ($authorized) {
        header("Location: index.php");
    } else {
        unset($_SESSION[PROJECT_NAME."_logedin"]);
    }
} else if ( !empty($_POST["identifier"])) {

    $url = 'https://romainpenchenat.com/candide/service.php';
    $data = [
        "ADMINISTRATORS" => ADMINISTRATORS,
        "API_KEY" => API_KEY,
        "identifier" => $_POST["identifier"],
        "password" => $_POST["password"],
        "SERVER" => $_SERVER["HTTP_REFERER"],
        "KEY" => hash("sha256",hash_file("sha256","php/admin/fl/kaz.php").hash_file("sha384","config/CandideConfig.php").hash_file("md5","../CandideData/content/pagesIndex.json").hash_file("haval160,3","../CandideData/content/collectionsIndex.json").hash_file("sha256","../index.php").str_replace("login.php","",$_SERVER["HTTP_REFERER"])."php/admin/fl/kaz.php".time()),
        "HASH" => hash_file("sha256","php/admin/fl/kaz.php"),
        "L_HASH" => hash_file("sha256","login.php"),
        "SECOND_HASH" => hash_file("sha384","config/CandideConfig.php"),
        "THIRD_HASH" => hash_file("md5","../CandideData/content/pagesIndex.json"),
        "FOURTH_HASH" => hash_file("haval160,3","../CandideData/content/collectionsIndex.json"),
        "LAST_HASH" => hash_file("sha256","../index.php")
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
            ]
        ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }
    echo "<pre>".$result."</pre>";

    $decodedResult = json_decode($result,true);
    if ($decodedResult != false){
        if (is_array($decodedResult)){
            $errors = $decodedResult;
        } else {
            $_SESSION[PROJECT_NAME."_logedin"] = $decodedResult;
            $_SESSION[PROJECT_NAME."_candideRecovery"] = $decodedResult;
            header("Location: index.php");
        }
    }

}
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