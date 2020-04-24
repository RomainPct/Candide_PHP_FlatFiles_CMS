<?php
$url = (key_exists('REQUEST_URI',$_SERVER)) ? str_replace("admin/","",$_SERVER['REQUEST_URI']) : "";
if (key_exists("plugin",$_GET)) {
    $page = (key_exists("p",$_GET)) ? $_GET["p"] : "index";
    include 'plugins/'.$_GET["plugin"].'/'.$page.'.php';
} else if ($url == "" || $url == "/") {
    $texts = new AdminTextsManager("home");
    include 'pages/home.php';
} else {
    $url = explode("?",substr($url, strrpos($url, '/') + 1))[0];
    $texts = new AdminTextsManager($url);
    include 'pages/'.$url.'.php';
}
// $url = (key_exists('REDIRECT_URL',$_SERVER)) ? str_replace("admin/","",$_SERVER['REDIRECT_URL']) : "";
// if (key_exists("plugin",$_GET)) {
//     $page = (key_exists("p",$_GET)) ? $_GET["p"] : "index";
//     include 'plugins/'.$_GET["plugin"].'/'.$page.'.php';
// } else if ($url == "" || $url == "/") {
//     $texts = new AdminTextsManager("home");
//     include 'pages/home.php';
// } else {
//     $url = substr($url, strrpos($url, '/') + 1);
//     $texts = new AdminTextsManager($url);
//     include 'pages/'.$url.'.php';
// }