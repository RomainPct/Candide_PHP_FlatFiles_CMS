<?php
include '../../CandideAdmin.php';
$_GET["updateAdminPlatform"] = true;
$c = new CandideCollectionItemAdministrator($_POST["candide_instance_name"],$_POST["candide_index"]);
$c->setData($_POST,$_FILES);