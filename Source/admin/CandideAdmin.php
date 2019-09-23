<?php
session_start();

const ADMIN_AUTOLOAD_DIRECTORIES = ["php/administration/"];

require 'Candide.php';

if ( key_exists(PROJECT_NAME."_logedin",$_SESSION)) {
    $authorized = false;
    foreach (ADMINISTRATORS as $user) {
        if ($_SESSION[PROJECT_NAME."_logedin"] == hash("sha256",$user[0]).hash("sha256",$user[1])) {
            $authorized = true;
        }
    }
    if (!$authorized) {
        header("Location: login.php");
    }
} else {
    header("Location: login.php");
}