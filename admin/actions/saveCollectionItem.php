<?php
include_once '../../Candide.php';
$_GET["updateAdminPlatform"] = true;
$c = new CandideCollectionItemAdministrator($_POST["candide_page_name"],$_POST["candide_index"]);
$c->setData($_POST,$_FILES);