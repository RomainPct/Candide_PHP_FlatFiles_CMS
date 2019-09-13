<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('ROOT_DIR', dirname(__FILE__));

include_once 'admin/config/CandideConfig.php';

include_once 'admin/php/base/Basic.class.php';

include_once 'admin/php/base/CandideBasic.class.php';
include_once 'admin/php/base/CandidePage.class.php';
include_once 'admin/php/base/CandideCollectionBasic.class.php';
include_once 'admin/php/base/CandideCollection.class.php';
include_once 'admin/php/base/CandideCollectionItem.class.php';