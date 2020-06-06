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

    use JsonReader {
        JsonReader::readJsonFile as capu_readJsonFile;
    }

    private $_indexAdmin;
    private $_phpFiles = [];
    private $_pageConfigFiles = [];
    private $_collectionConfigFiles = [];
    private $_candideInstances = [];

    /**
     * Construct CandideAdminPlatformUpdater
     */
    public function __construct() {
        $this->_indexAdmin = new CandideIndexAdmin();
        // Get all files in each CANDIDE_FILES_FOLDERS
        foreach (CANDIDE_FILES_FOLDERS as $folder) {
            $this->_phpFiles = array_merge($this->_phpFiles, glob(ROOT_DIR.$folder.'*.php'));
        }
        $this->_pageConfigFiles = glob(ROOT_DIR.'/admin/config/structures/pages/{**/*,*}.json', GLOB_BRACE);
        $this->_collectionConfigFiles = glob(ROOT_DIR.'/admin/config/structures/collections/{**/*,*}.json', GLOB_BRACE);
    }

    /**
     * Update the whole data structure according to php files
     *
     * @return void
     */
    public function update() {
        // Set variable to allow structure and data to be update
        $_GET['updateAdminPlatform'] = true;
        foreach ($this->_phpFiles as $file) {
            $this->analyzePhpFile($file);
        }
        foreach ($this->_pageConfigFiles as $pageConfigPath) {
            $this->recordPageConfigFile($pageConfigPath);
        }
        foreach ($this->_collectionConfigFiles as $collectionConfigPath) {
            $this->recordCollectionConfigFile($collectionConfigPath);
        }
        foreach ($this->_candideInstances as $c) if (is_a($c, 'CandideBasic')) {
            $c->save();
        }
        $this->_indexAdmin->saveIndex();
        echo '\n\n -> save\n';
    }

    /**
     * Analyze a json config file and record candide collection instance
     *
     * @param String $collectionConfigFilePath [File path]
     * @return void
     */
    private function recordCollectionConfigFile(String $collectionConfigFilePath) {
        $collectionName = $this->getInstanceNameFromFilePath($collectionConfigFilePath);
        $config = $this->capu_readJsonFile($collectionConfigFilePath);
        if (key_exists('instances', $config)) {
            foreach ($config['instances'] as $instanceName) {
                $this->recordCollectionConfig($instanceName, $config);
            }
        } else {
            $this->recordCollectionConfig($collectionName, $config);
        }
    }

    /**
     * Record a collection at a specific name with a config
     *
     * @param String $name [Collection name]
     * @param Array $config [Config data]
     * @return void
     */
    private function recordCollectionConfig(String $name, Array $config) {
        if (key_exists('generalItem', $config)) {
            $collection = new CandideCollection($name);
            foreach ($config['generalItem'] as $title => $field) {
                $collection->items()[0]->setElementFromConfigFile($title, $field);
            }
            $this->recordCandideInstance($collection);
            usleep(10);
        }
        if (key_exists('detailedItem', $config)) {
            $collectionItem = new CandideCollectionItem($name, null);
            foreach ($config['detailedItem'] as $title => $field) {
                $collectionItem->setElementFromConfigFile($title, $field);
            }
            $this->recordCandideInstance($collectionItem);
            usleep(10);
        }
    }

    /**
     * Analyze a json config file and record candide page instance
     *
     * @param String $pageConfigFilePath [File path]
     * @return void
     */
    private function recordPageConfigFile(String $pageConfigFilePath) {
        $c = new CandidePage($this->getInstanceNameFromFilePath($pageConfigFilePath));
        foreach ($this->capu_readJsonFile($pageConfigFilePath) as $title => $field) {
            $c->setElementFromConfigFile($title, $field);
        }
        $this->recordCandideInstance($c);
        usleep(10);
    }

    private function getInstanceNameFromFilePath(String $path) {
        return str_replace('.json', '', basename($path));
    }

    /**
     * Analyze a php file and record candide instances
     *
     * @param String $file [File path]
     * @return void
     */
    private function analyzePhpFile(String $file) {
        echo '\n\n\n___________\n Begin file analyse of : '.$file.'\n';
        require $file;
        // If Candide is used in the page
        if (isset($c)) {
            // If there is a single instance of Candide
            if (is_object($c)) {
                $this->recordCandideInstance($c);
            }
            // If there is many instances of Candide
            if (is_array($c)) {
                foreach($c as $candideInstance) {
                    $this->recordCandideInstance($candideInstance);
                }
            }
        }
        echo '\n\n'.$file.' ANALYSED\n------------';
        unset($c);
        usleep(10);
    }

    /**
     * Record a candide instance to save it at the end
     *
     * @param $candideInstance
     * @return void
     */
    private function recordCandideInstance($candideInstance) {
        if (!is_a($candideInstance, 'CandideBasic')) { return; }
        $type = null;
        if (is_a($candideInstance, 'CandidePage')) {
            $type = 'page';
        } elseif (is_a($candideInstance, 'CandideCollection')) {
            $type = 'collection';
        } elseif (is_a($candideInstance, 'CandideCollectionItem')) {
            $type = 'collectionItem';
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