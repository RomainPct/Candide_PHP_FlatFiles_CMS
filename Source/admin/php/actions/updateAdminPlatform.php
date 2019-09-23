<?php
include '../../CandideAdmin.php';

$files = [];
// Get all files in each CANDIDE_FILES_FOLDERS
foreach (CANDIDE_FILES_FOLDERS as $folder) {
    $files = array_merge($files, glob(ROOT_DIR.$folder."*.php"));
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
            $indexAdmin->updateCandideInstance($c);
        }
        // If there is many instances of Candide
        else if (is_array($c)) {
            foreach($c as $candideInstance){
                $indexAdmin->updateCandideInstance($candideInstance);
            }
        }

    }
    echo "\n\n".$file." ANALYSED\n------------";
    unset($c);
    usleep(10);
}
$indexAdmin->saveIndex();