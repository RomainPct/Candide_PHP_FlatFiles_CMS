<?php
include_once '../../Candide.php';
$_GET["updateAdminPlatform"] = true;
$c = new CandidePageAdministrator($_POST["pageName"]);
$c->setData($_POST,$_FILES);