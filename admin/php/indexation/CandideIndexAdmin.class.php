<?php


class CandideIndexAdmin extends CandideIndexBasic {

    public function __construct() {
        $this->_pages = [];
        $this->_collections = [];
    }

    public function newPage($title) {
        if (!in_array($title,$this->_pages)) {
            $this->_pages[] = $title;
        }
    }

    public function newCollection($title) {
        if (!in_array($title,$this->_collections)) {
            $this->_collections[] = $title;
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
    }

    private function savePages(){
        file_put_contents(self::PAGES_INDEX_URL,json_encode($this->_pages));
    }
    private function saveCollections(){
        file_put_contents(self::COLLECTION_INDEX_URL,json_encode($this->_collections));
    }

}