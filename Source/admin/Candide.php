<?php
// Set errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define root directory
define('ROOT_DIR', dirname(dirname(__FILE__)));

// Load config
include 'config/CandideConfig.php';

const AUTOLOAD_DIRECTORIES = [ 'php/general/', 'php/user_interactive/'];
const FILE_EXTENSIONS = ['class', 'trait'];


spl_autoload_register(function($name) {
    $directories = defined("ADMIN_AUTOLOAD_DIRECTORIES") ? array_merge(AUTOLOAD_DIRECTORIES,ADMIN_AUTOLOAD_DIRECTORIES) : AUTOLOAD_DIRECTORIES;
    foreach ($directories as $dir) {
        foreach (FILE_EXTENSIONS as $extension) {
            $path = ROOT_DIR."/admin/".$dir.$extension."/".sprintf('%s.'.$extension.'.php', $name);
            if (file_exists($path)) {
                include $path;
                return ;
            }
        }
    }
});
