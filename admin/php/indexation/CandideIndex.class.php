<?php

class CandideIndex extends CandideIndexBasic {

    public function __construct() {
        $this->_pages = (file_exists(self::PAGES_INDEX_URL)) ? json_decode(file_get_contents(self::PAGES_INDEX_URL)) : [];
        $this->_collections = (file_exists(self::COLLECTION_INDEX_URL)) ? json_decode(file_get_contents(self::COLLECTION_INDEX_URL)) : [];
    }

    public function getPageName($index):String {
        return $this->_pages[$index];
    }
    public function getPage($index):String {
        return $this->formatTitle($this->_pages[$index]);
    }
    public function countPages():Int {
        return count($this->_pages);
    }

    public function getCollectionName($index):String {
        return $this->_collections[$index];
    }
    public function getCollection($index):String {
        return $this->formatTitle($this->_collections[$index]);
    }
    public function countCollections():Int {
        return count($this->_collections);
    }

}