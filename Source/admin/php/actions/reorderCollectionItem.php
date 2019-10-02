<?php
ignore_user_abort(true);
set_time_limit(0);
include '../../CandideAdmin.php';
$manager = new CandideCollectionReorder($_POST["candide_instance_name"]);
$newIds = $manager->reorder($_POST["reorderedItemIndex"],$_POST["insertBeforeIndex"]);
