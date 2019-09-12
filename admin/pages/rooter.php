<?php
$url = (key_exists('REDIRECT_URL',$_SERVER)) ? str_replace("admin/","",$_SERVER['REDIRECT_URL']) : "";
if ($url == "" || $url == "/") {
    include_once 'pages/home.php';
} else {
    include_once 'pages'.$url.'.php';
}