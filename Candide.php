<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('ROOT_DIR', dirname(__FILE__));

include_once 'admin/config/CandideConfig.php';

include_once 'admin/php/Basic.class.php';

include_once 'admin/php/CandideBasic.class.php';
include_once 'admin/php/CandidePage.class.php';
include_once 'admin/php/CandideCollection.class.php';
include_once 'admin/php/CandideCollectionItem.class.php';

include_once 'admin/php/index/CandideIndexBasic.class.php';
include_once 'admin/php/index/CandideIndex.class.php';
include_once 'admin/php/index/CandideIndexAdmin.class.php';