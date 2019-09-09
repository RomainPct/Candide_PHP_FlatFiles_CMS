<?php


class CandideIndexAdmin extends CandideIndexBasic {

    use Administrator;

    protected $_newPages;
    protected $_newCollections;

    public function newPage($title) {
        if (!in_array($title,$this->_newPages)) {
            $this->_newPages[] = $title;
        }
    }

    public function newCollection($title) {
        if (!in_array($title,$this->_newCollections)) {
            $this->_newCollections[] = $title;
        }
    }

    function updateCandideInstance($candideInstance){
        // Call the end function to save data changes
        if (is_a($candideInstance, "CandideBasic")) {
            $candideInstance->save();
        }
        // Manage pages indexation
        if (is_a($candideInstance, "CandidePage")) {
            $this->newPage($candideInstance->getPage());
        } elseif (is_a($candideInstance, "CandideCollection")) {
            $this->newCollection($candideInstance->getPage());
        }
    }

    public function saveIndex(){
        $this->savePages();
        $this->saveCollections();
        $this->removeOldData();
    }

    private function savePages(){
        file_put_contents(self::PAGES_INDEX_URL,json_encode($this->_newPages));
    }

    private function saveCollections(){
        file_put_contents(self::COLLECTION_INDEX_URL,json_encode($this->_newCollections));
    }

    private function removeOldData(){
        $oldFolders = array_merge($this->_pages,$this->_collections);
        $newFolders = array_merge($this->_newPages,$this->_newCollections);
        $folders = array_diff($oldFolders,$newFolders);
        foreach($folders as $folder) {
            $this->deleteFiles(self::DATA_DIRECTORY.$folder);
            $this->deleteFiles(self::FILES_DIRECTORY.$folder);
        }
    }

}