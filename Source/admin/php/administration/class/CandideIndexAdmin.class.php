<?php
/**
 * CandideIndexAdmin.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Manager for Pages/Collections/Plugins indexes
 * 
 * @since 1.0
 * Basic < CandideIndexBasic < CandideIndexAdmin
 * 
 */
class CandideIndexAdmin extends CandideIndexBasic {

    use BackendPluginNotifier;

    protected $_newPages = [], $_newCollections = [];

    /**
     * Index a page
     *
     * @param String $title
     * @return void
     */
    public function newPage(String $title) {
        if (!in_array($title,$this->_newPages)) {
            $this->_newPages[] = $title;
        }
    }

    /**
     * Index a collection
     *
     * @param String $title
     * @return void
     */
    public function newCollection(String $title) {
        if (!in_array($title,$this->_newCollections)) {
            $this->_newCollections[] = $title;
        }
    }

    /**
     * Update a candide instance
     *
     * @param CandideBasic $candideInstance
     * @return void
     */
    function updateCandideInstance(CandideBasic $candideInstance){
        // Manage pages indexation
        if (is_a($candideInstance, "CandidePage")) {
            $this->newPage($candideInstance->getInstanceName());
        } elseif (is_a($candideInstance, "CandideCollection")) {
            $this->newCollection($candideInstance->getInstanceName());
        }
    }

    /**
     * Save index updates
     *
     * @return void
     */
    public function saveIndex(){
        file_put_contents(self::PAGES_INDEX_URL,json_encode($this->_newPages));
        file_put_contents(self::COLLECTION_INDEX_URL,json_encode($this->_newCollections));
        $this->cleanOldData();
        $this->sendNotification(Notification::CANDIDE_UPDATED);
    }

    /**
     * Remove useless folders of old pages and collections
     *
     * @return void
     */
    private function cleanOldData(){
        $oldFolders = array_merge($this->_pages,$this->_collections);
        $newFolders = array_merge($this->_newPages,$this->_newCollections);
        $folders = array_diff($oldFolders,$newFolders);
        foreach($folders as $folder) {
            $this->deleteFiles(self::DATA_DIRECTORY.$folder);
            $this->deleteFiles(self::FILES_DIRECTORY.$folder);
        }
    }

}