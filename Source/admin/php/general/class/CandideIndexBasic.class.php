<?php

// Basic < CandideIndexBasic

class CandideIndexBasic extends Basic  {

    use JsonReader;

    protected $_pages;
    protected $_collections;

    const PAGES_INDEX_URL = self::DATA_DIRECTORY."pagesIndex.json";
    const COLLECTION_INDEX_URL = self::DATA_DIRECTORY."collectionsIndex.json";

    /**
     * CandideIndexBasic constructor
     * Set $_pages & $_collections from json indexes files
     */
    public function __construct() {
        $this->_pages = $this->readJsonFile(self::PAGES_INDEX_URL);
        $this->_collections = $this->readJsonFile(self::COLLECTION_INDEX_URL);
    }

}