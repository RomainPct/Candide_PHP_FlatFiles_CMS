<?php
session_start();

if ( key_exists(PROJECT_NAME."_user",$_SESSION)) {
    echo $_SESSION[PROJECT_NAME."_admin"];
} else {
    //header("Location: login.php");
}

include_once 'php/admin/Administrator.trait.php';

include_once 'php/admin/CandidePageAdministrator.class.php';
include_once 'php/admin/CandideCollectionAdministrator.class.php';
include_once 'php/admin/CandideCollectionItemAdministrator.class.php';