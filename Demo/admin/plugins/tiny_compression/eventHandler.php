<?php
// Detect if the event is the one we want
$evt = $_GET["event"];
if ($evt == Notification::NEW_PICTURE_SAVED || $evt == Notification::CANDIDE_UPDATED) {

    // Require needed php files
    require_once "class/CompressorProgrammer.class.php";
    require_once "tinify-php-master/lib/Tinify/Exception.php";
    require_once "tinify-php-master/lib/Tinify/Client.php";
    require_once "tinify-php-master/lib/Tinify.php";

    // Init needed infos
    $helper = new PluginHelper("tiny_compression");
    $api_key = $helper->getInfoInConfig("tinypng_key");

    try {
        // Check Tinify API Key
        \Tinify\setKey($api_key);
        \Tinify\validate();

        // Manage files queue
        $compressor = new CompressorProgrammer();
        if ($evt == Notification::NEW_PICTURE_SAVED) {
            $compressor->addToQueue($_GET["informations"]["url"]);
        }

        // Launch compression if needed
        if (!$compressor->isRunning()) {
            $helper->async("async/compression.php");   
            // echo "Tinify compressions this month : ".\Tinify\compressionCount();
        }
    } catch(\Tinify\Exception $e) {
        trigger_error($e,E_USER_ERROR);
    }
}