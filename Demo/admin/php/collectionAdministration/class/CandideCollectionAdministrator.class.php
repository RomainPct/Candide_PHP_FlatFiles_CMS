<?php
/**
 * CandideCollectionAdministrator.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Collection managment on admin interface (Create/Remove/Update an item)
 * 
 * @since 1.0
 * Basic < CandideBasic < CandideCollectionBasic < CandideCollection < CandideCollectionAdministrator
 * 
 */
class CandideCollectionAdministrator extends CandideCollection {

    /**
     * CandideCollectionAdministrator constructor which set _structure
     *
     * @param String $instanceName [Collection name]
     */
    public function __construct(String $instanceName){
        parent::__construct($instanceName);
        $this->_structure = $this->readJsonFile($this->getStructureUrl());
    }

    /**
     * Generate a new item id
     *
     * @return Int
     */
    public function getNewId():Int {
        if (count($this->_data) == 0) {
            return 0;
        } else {
            return intval(end($this->_data)["id"]) + 1;
        }
    }

    /**
     * Remove an item from the collection
     *
     * @param Int $id [Item to remove id]
     * @return void
     */
    public function removeItem(Int $id){
        unset($this->_data[$id]);
        $this->saveData();
    }

    /**
     * Return the data of a specific item
     *
     * @param Int $id [Item id]
     * @return Array [Item data]
     */
    public function getDataForItemWithId(Int $id):Array{
        return $this->_data[$id];
    }

    /**
     * Return all item ids
     *
     * @return Array [Item ids]
     */
    public function getIds():Array {
        return array_keys($this->_data);
    }

    /**
     * Return all CandideCollectionBaseItemAdministrator for the current collection
     *
     * @return CandideCollectionBaseItemAdministrator[] [An array of CandideCollectionBaseItemAdministrator]
     */
    public function items():Array {
        $items = array_map(function($item){
            return new CandideCollectionBaseItemAdministrator($item,$this->_structure);
        },$this->_data);
        return $items;
    }

    /**
     * Set data for a specific collection item
     *
     * @param Array $texts [Input values from HTML form]
     * @param Array $files [File input values from HTML form]
     * @param Int $id [Item id]
     * @return Array [Return item data]
     */
    public function setData(Array $texts, Array $files, Int $id):Array{
        if (!key_exists($id,$this->_data)) {
            $this->_data[$id] = [];
            $this->_data[$id]["id"] = $id;
        }
        $this->_data[$id] = array_merge($this->_structure,$this->_data[$id]);
        $this->setTexts($texts,$id);
        $this->setImages($files,$id);
        $this->saveData();
        return $this->_data[$id];
    }

    /**
     * Set texts for a specific collection item
     *
     * @param Array $texts [Input values from HTML form]
     * @param Int $id [Item id]
     * @return void
     */
    private function setTexts(Array $texts, Int $id) {
        foreach ($this->_structure as $key => $value) {
            if (key_exists($key,$texts)) {
                $this->_data[$id][$key]['data'] = $texts[$key];
            }
        }
    }

    /**
     * Set images for a specific collection item
     *
     * @param Array $newFiles [File input values from HTML form]
     * @param Int $id [Item id]
     * @return void
     */
    private function setImages(Array $newFiles, Int $id){
        foreach ($newFiles as $key => $url) {
            $this->_data[$id][$key]['data'] = $url;
        }
    }

}