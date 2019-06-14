<?php
include_once '../Candide.php';
$exceptions = ["../Candide.php"];
// Parcourir tout les fichiers *.php dans ../ direct
$allFiles = array_map(function($f){
    return glob("..".$f."*.php");
},CANDIDE_FILES_FOLDERS);
$files = [];
foreach($allFiles as $filesInDir){
    $files = array_merge($files,array_diff($filesInDir,$exceptions));
}
var_dump($files);

// Fonction d'update pour chaque c
function updatePageForVariable($candide,$indexAdmin){
    if (isset($candide)) {
        if (is_a($candide, "CandideBasic")) {
            $candide->end();
        }
        if (is_a($candide, "CandidePage")) {
            $indexAdmin->newPage($candide->getPage());
        } elseif (is_a($candide, "CandideCollection")) {
            $indexAdmin->newCollection($candide->getPage());
        }
        unset($candide);
    }
}

// Effectuer les requetes pour update le dossier Data
$indexAdmin = new CandideIndexAdmin();
$_GET["updateAdminPlatform"] = true;
foreach ($files as $file) {
    echo $file." ANALYSED";
    require $file;
    if (isset($c)) {
        updatePageForVariable($c,$indexAdmin);
    }
    if (isset($c1)) {
        updatePageForVariable($c1,$indexAdmin);
    }
    if (isset($c2)) {
        updatePageForVariable($c2,$indexAdmin);
    }
    if (isset($c3)) {
        updatePageForVariable($c3,$indexAdmin);
    }
    if (isset($c4)) {
        updatePageForVariable($c4,$indexAdmin);
    }
    if (isset($c5)) {
        updatePageForVariable($c5,$indexAdmin);
    }
    usleep(10);
}
$indexAdmin->end();