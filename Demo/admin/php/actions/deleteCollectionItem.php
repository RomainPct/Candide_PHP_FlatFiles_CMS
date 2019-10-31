<?php
include '../../CandideAdmin.php';
$_GET["updateAdminPlatform"] = true;
$c = new CandideCollectionItemAdministrator($_GET["candide_instance_name"],$_GET["candide_index"]);
$c->deleteThisItem();