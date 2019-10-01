<?php
/**
 * CandideIndexBasic.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Reader for collections and pages indexes
 * 
 * @since 1.0
 * Basic < CandideIndexBasic
 * 
 */
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