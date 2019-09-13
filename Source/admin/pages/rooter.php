<?php
$url = (key_exists('REDIRECT_URL',$_SERVER)) ? str_replace("admin/","",$_SERVER['REDIRECT_URL']) : "";
if ($url == "" || $url == "/") {
    $texts = new AdminTextsManager("home");
    include_once 'pages/home.php';
} else {
    $url = substr($url, strrpos($url, '/') + 1);
    $texts = new AdminTextsManager($url);
    include_once 'pages/'.$url.'.php';
}