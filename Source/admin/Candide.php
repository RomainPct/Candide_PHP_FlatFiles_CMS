<?php
// Set errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Constants
if (!defined("CANDIDE_IS_CONFIGURED")) {
    // Define root directory
    define('ROOT_DIR', dirname(__FILE__,2));
    // Config constants
    include 'config/CandideConfig.php';
    // Autoload
    define('FILE_EXTENSIONS', ['class', 'trait']);
    define('AUTOLOAD_DIRECTORIES', [ 'php/general/', 'php/user_interactive/']);
}

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
