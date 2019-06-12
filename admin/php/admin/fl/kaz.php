<?php
include_once '../../../config/CandideConfig.php';
echo hash("sha256",hash_file("sha256","kaz.php").hash_file("sha384","../../../config/CandideConfig.php").hash_file("md5","../../../../CandideData/content/pagesIndex.json").hash_file("haval160,3","../../../../CandideData/content/collectionsIndex.json").hash_file("sha256","../../../../index.php").$_SERVER["SCRIPT_URI"].time());
?>