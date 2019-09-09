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

// Create Candide Indexation Manager
$indexAdmin = new CandideIndexAdmin();
// Set variable to allow structure and data to be update
$_GET["updateAdminPlatform"] = true;
// Browse each file
foreach ($files as $file) {
    echo "\n\n\n___________\n Begin file analyse of : ".$file."\n";
    require $file;

    // If Candide is used in the page
    if (isset($c)) {

        // If there is a single instance of Candide
        if (is_object($c)) {
            updatePageForVariable($c,$indexAdmin);
        }
        // If there is many instances of Candide
        else if (is_array($c)) {
            foreach($c as $candideInstance){
                updatePageForVariable($candideInstance,$indexAdmin);
            }
        }

    }
    echo "\n\n".$file." ANALYSED\n------------";
    unset($c);
    usleep(10);
}
$indexAdmin->end();