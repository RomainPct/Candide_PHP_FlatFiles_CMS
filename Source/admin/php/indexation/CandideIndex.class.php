<?php

class CandideIndex extends CandideIndexBasic {

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