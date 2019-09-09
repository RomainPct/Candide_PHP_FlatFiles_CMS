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

include_once 'php/administration/Administrator.trait.php';

include_once 'php/administration/CandidePageAdministrator.class.php';
include_once 'php/administration/CandideCollectionAdministrator.class.php';
include_once 'php/administration/CandideCollectionItemAdministrator.class.php';

include_once 'php/indexation/CandideIndexBasic.class.php';
include_once 'php/indexation/CandideIndex.class.php';
include_once 'php/indexation/CandideIndexAdmin.class.php';