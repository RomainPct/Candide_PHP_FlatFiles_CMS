<?php
include_once '../../../Candide.php';
$dest = "/CandideData/".$_POST['destination']."/wysiwyg/";
if (!file_exists(ROOT_DIR.$dest)) {
    mkdir(ROOT_DIR.$dest,0777,true);
}
$file = $_FILES["file"];
$fileDest = $dest.time().$file["name"];
if (move_uploaded_file($file["tmp_name"],ROOT_DIR.$fileDest)){
    echo $fileDest;
} else {
    echo "ERROR";
}