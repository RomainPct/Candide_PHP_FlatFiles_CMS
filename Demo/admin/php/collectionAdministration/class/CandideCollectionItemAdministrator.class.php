<?php
/**
 * CandideCollectionItemAdministrator.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Specific CandideCollectionItemAdministrator for admin side which manage Create/Remove/update an item
 * 
 * @since 1.0
 * Basic < CandideBasic < CandideCollectionBasic < CandideCollectionItem < CandideCollectionItemAdministrator
 * 
 */
class CandideCollectionItemAdministrator extends CandideCollectionItem {

    use FieldsGenerator, WysiwygFiles, JsonReader;

    private $_collectionAdministrator;
    private $_fullStructure;
    private $_fullData = [];
    private $_newItem = false;

    /**
     * CandideCollectionItemAdministrator constructor
     *
     * @param String $instanceName [Collection name]
     * @param Mixed $id [Item id or "newItem"]
     */
    public function __construct(String $instanceName, $id) {
        $this->_collectionAdministrator = new CandideCollectionAdministrator($instanceName);
        if ($id === 'newItem') {
            $id = $this->_collectionAdministrator->getNewId();
            $this->_newItem = true;
        }
        parent::__construct($instanceName,$id);
        $this->_structure = $this->readJsonFile($this->getStructureUrl());
        $this->_fullStructure = array_merge($this->_collectionAdministrator->_structure,$this->_structure);
        if (!$this->_newItem) {
            $this->_fullData = array_merge($this->_data,$this->_collectionAdministrator->getDataForItemWithId($this->_id));
        }
    }

    /**
     * Echo page title
     *
     * @param AdminTextsManager $texts
     * @return void
     */
    public function getTitle(AdminTextsManager $texts){
        if ($this->_newItem){
            echo $texts->get("new_item_in_collection").' "'.$this->formatTitle($this->_instanceName).'"';
        } else {
            echo $texts->get("edit_item_of_the_collection").' "'.$this->formatTitle($this->_instanceName).'"';
        }
    }

    /**
     * Echo cta title
     *
     * @param AdminTextsManager $texts
     * @return void
     */
    public function getCallToActionText(AdminTextsManager $texts){
        echo ($this->_newItem) ? $texts->get("add") : $texts->get("save");
    }

    /**
     * Echo all field of the current Candide Page
     *
     * @return void
     */
    public function getFields() {
        foreach ($this->_fullStructure as $key => $value){
            $data = (key_exists($key,$this->_fullData) && key_exists('data',$this->_fullData[$key])) ? $this->_fullData[$key]['data'] : "";
            echo $this->getField($key,$data,$value);
        }
    }

    /**
     * Remove everyting about current item
     *
     * @return void
     */
    public function deleteThisItem(){
        // Supprimer les données sur l'item
        $this->deleteFiles(self::DATA_DIRECTORY.$this->_instanceName."/items/".$this->_id);
        $this->deleteFiles(self::FILES_DIRECTORY.$this->_instanceName."/".$this->_id);
        // Supprimer l'item de $this->_collectionAdministrator->_data
        $this->_collectionAdministrator->removeItem($this->_id);
    }

    /**
     * Set data for current item
     *
     * @param Array $texts [Input values from HTML form]
     * @param Array $files [File input values from HTML form]
     * @return void
     */
    public function setData(Array $texts, Array $files){
        $this->_data = array_merge($this->_structure,$this->_data);
        $newFiles = $this->setImages($files, $texts, $this->_data);
        $this->setTexts($texts);
        $this->saveData();
        $collectionData = $this->_collectionAdministrator->setData($texts,$newFiles,$this->_id);
        $this->cleanWysiwygFiles(self::FILES_DIRECTORY.$this->_instanceName."/".$this->_id,array_merge($collectionData,$this->_data));
        echo $this->_id;
    }

    /**
     * Set texts for current item
     *
     * @param Array $texts [Input values from HTML form]
     * @return void
     */
    private function setTexts(Array $texts) {
        foreach ($this->_structure as $key => $value) {
            if (key_exists($key,$texts)) {
                $this->_data[$key]['data'] = $texts[$key];
            } else if (!key_exists('data',$this->_data[$key])) {
                $this->_data[$key]['data'] = 'undefined';
            }
        }
    }

    /**
     * Set images for current item
     *
     * @param Array $files [File input values from HTML form]
     * @param Array $texts [Input values from HTML form]
     * @param Array $infos [Current item data]
     * @return Array
     */
    private function setImages(Array $files, Array &$texts, Array $infos) : Array {
        $newFiles = [];
        foreach ($files as $fieldName => $file) {
            $dest = $this->getInstanceName()."/".$this->_id;
            if ($file["size"] != 0 && strpos($fieldName,"image_") === 0) {
                $data = key_exists($fieldName,$this->_fullData) ? $this->_fullData[$fieldName] : [];
                $url = $this->savePicture($fieldName, $file, $dest, $data, $this->_fullStructure[$fieldName]);
                $newFiles[$fieldName] = $url;
                if (key_exists($fieldName,$this->_data)) {
                    $this->_data[$fieldName]['data'] = $url;
                }
            } else if ($file["size"] != 0) {
                $url = $this->saveWysiwygFile($fieldName,$file,$dest."/wysiwyg",$texts,$infos);
            }
        }
        return $newFiles;
    }

    /**
     * Update only the structure saved in the item data
     *
     * @return void
     */
    public function updateStructuredData(){
        $data = $this->_structure;
        array_walk($this->_data,function($value,$key) use (&$data) {
            $data[$key]["data"] = $value["data"];
        });
        $this->_data = $data;
        $this->saveData();
    }

}