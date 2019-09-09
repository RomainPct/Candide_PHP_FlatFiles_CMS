<?php


class CandideIndexBasic extends Basic  {

    protected $_pages;
    protected $_collections;

    public function __construct() {
        $this->_pages = (file_exists(self::PAGES_INDEX_URL)) ? json_decode(file_get_contents(self::PAGES_INDEX_URL)) : [];
        $this->_collections = (file_exists(self::COLLECTION_INDEX_URL)) ? json_decode(file_get_contents(self::COLLECTION_INDEX_URL)) : [];
    }

    const PAGES_INDEX_URL = self::DATA_DIRECTORY."pagesIndex.json";
    const COLLECTION_INDEX_URL = self::DATA_DIRECTORY."collectionsIndex.json";

}