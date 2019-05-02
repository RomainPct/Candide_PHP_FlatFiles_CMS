<?php
include_once '../../Candide.php';
$_GET["updateAdminPlatform"] = true;
$c = new CandideCollectionItemAdministrator($_GET["candide_page_name"],$_GET["candide_index"]);
$c->deleteThisItem();