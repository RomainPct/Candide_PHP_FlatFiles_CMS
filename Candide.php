<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('ROOT_DIR', dirname(__FILE__));

include_once 'Candide/Basic.class.php';

include_once 'Candide/admin/Administrator.trait.php';

include_once 'Candide/CandideBasic.class.php';
include_once 'Candide/CandidePage.class.php';
include_once 'Candide/CandideCollection.class.php';
include_once 'Candide/CandideCollectionItem.class.php';

include_once 'Candide/index/CandideIndexBasic.class.php';
include_once 'Candide/index/CandideIndex.class.php';
include_once 'Candide/index/CandideIndexAdmin.class.php';

include_once 'Candide/admin/CandidePageAdministrator.class.php';
include_once 'Candide/admin/CandideCollectionAdministrator.class.php';
include_once 'Candide/admin/CandideCollectionItemAdministrator.class.php';