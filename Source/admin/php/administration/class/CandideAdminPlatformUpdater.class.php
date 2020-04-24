<?php
/**
 * AdminPlatformUpdater.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

class CandideAdminPlatformUpdater {

    private $_indexAdmin;
    private $_files = [];
    private $_candideInstances = [];

    /**
     * Construct CandideAdminPlatformUpdater
     */
    public function __construct() {
        $this->_indexAdmin = new CandideIndexAdmin();
        // Get all files in each CANDIDE_FILES_FOLDERS
        foreach (CANDIDE_FILES_FOLDERS as $folder) {
            $this->_files = array_merge($this->_files, glob(ROOT_DIR.$folder."*.php"));
        }
    }

    /**
     * Update the whole data structure according to php files
     *
     * @return void
     */
    public function update() {
        // Set variable to allow structure and data to be update
        $_GET["updateAdminPlatform"] = true;
        foreach ($this->_files as $file) {
            echo "\n\n\n___________\n Begin file analyse of : ".$file."\n";
            require $file;
            $this->recordCurrentFileCandideInstances($c);
            echo "\n\n".$file." ANALYSED\n------------";
            unset($c);
            usleep(10);
        }
        foreach ($this->_candideInstances as $c) if (is_a($c, "CandideBasic")) {
            $c->save();
        }
        $this->_indexAdmin->saveIndex();
        echo "\n\n -> save\n";
    }

    /**
     * Record candide instance to save it at the end
     *
     * @param $c [Potential candide instance to record]
     * @return void
     */
    private function recordCurrentFileCandideInstances($c) {
        // If Candide is used in the page
        if (!isset($c)) { return; }
        // If there is a single instance of Candide
        if (is_object($c)) {
            $this->recordCandideInstance($c);
            return;
        }
        // If there is many instances of Candide
        if (is_array($c)) {
            foreach($c as $candideInstance){
                $this->recordCandideInstance($candideInstance);
            }
            return;
        }
    }

    /**
     * Record a candide instance to save it at the end
     *
     * @param $candideInstance
     * @return void
     */
    private function recordCandideInstance($candideInstance) {
        if (!is_a($candideInstance, "CandideBasic")) { return; }
        $type = null;
        if (is_a($candideInstance, "CandidePage")) {
            $type = "page";
        } elseif (is_a($candideInstance, "CandideCollection")) {
            $type = "collection";
        } elseif (is_a($candideInstance, "CandideCollectionItem")) {
            $type = "collectionItem";
        }
        $key = $type.'_'.$candideInstance->getInstanceName();
        if (key_exists($key, $this->_candideInstances)) {
            $this->_candideInstances[$key]->mergeWith($candideInstance);
        } else {
            $this->_candideInstances[$key] = $candideInstance;
        }
        $this->_indexAdmin->updateCandideInstance($this->_candideInstances[$key]);
    }


}