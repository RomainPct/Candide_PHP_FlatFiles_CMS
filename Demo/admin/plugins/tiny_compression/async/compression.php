<?php
// To allow async call
ignore_user_abort(true);
set_time_limit(0);

// Require CompressorProgrammer
require "../../../CandideAdmin.php";
require "../class/CompressorProgrammer.class.php";
$compressor = new CompressorProgrammer();
$compressor->run();

// Require Tinify api
require "../tinify-php-master/lib/Tinify/Exception.php";
require "../tinify-php-master/lib/Tinify/ResultMeta.php";
require "../tinify-php-master/lib/Tinify/Result.php";
require "../tinify-php-master/lib/Tinify/Source.php";
require "../tinify-php-master/lib/Tinify/Client.php";
require "../tinify-php-master/lib/Tinify.php";

// Login Tinify API
$helper = new PluginHelper("tiny_compression");
\Tinify\setKey($helper->getInfoInConfig("tinypng_key"));

while (($img_url = $compressor->getNextFileToCompress()) !== null) {
    if (file_exists($img_url)) {
        error_log("Launch optimization of ".$img_url);
        try {
            $source = \Tinify\fromFile($img_url);
            $source->toFile($img_url);
            error_log("Image optimized at ".$img_url);
            $compressor->removeFromQueue($img_url);
        } catch(\Tinify\AccountException $e) {
            error_log("Tinify AccountException error is: " . $e->getMessage());
        } catch(\Tinify\ClientException $e) {
            error_log("Tinify ClientException error is: " . $e->getMessage());
        } catch(\Tinify\ServerException $e) {
            error_log("Tinify ServerException error is: " . $e->getMessage());
        } catch(\Tinify\ConnectionException $e) {
            error_log("Tinify ConnectionException error is: " . $e->getMessage());
        } catch(Exception $e) {
            error_log("Tinify Exception error is: " . $e->getMessage());
        }   
    } else {
        error_log("File no longer exists at path : ".$img_url);
        $compressor->removeFromQueue($img_url);
    }
    // Prevent Tinify API error "too much request at the time"
    sleep(1);
}