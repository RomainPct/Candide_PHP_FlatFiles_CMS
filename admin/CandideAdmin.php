<?php
session_start();

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

include_once 'php/admin/Administrator.trait.php';

include_once 'php/admin/CandidePageAdministrator.class.php';
include_once 'php/admin/CandideCollectionAdministrator.class.php';
include_once 'php/admin/CandideCollectionItemAdministrator.class.php';