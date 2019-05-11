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

    public function end(){
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