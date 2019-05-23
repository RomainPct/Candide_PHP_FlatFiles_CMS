<?php
$dest = "../../CandideData/".$_POST['destination']."/trix/";
mkdir($dest,0777,true);
$file = $_FILES["file"];
$fileDest = $dest.time().$file["name"];
if (move_uploaded_file($file["tmp_name"],$fileDest)){
    echo ltrim($fileDest,"../..");
} else {
    echo "ERROR";
}