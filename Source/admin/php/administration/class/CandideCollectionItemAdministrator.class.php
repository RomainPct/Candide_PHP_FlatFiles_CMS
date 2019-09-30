<?php

// Basic < CandideBasic < CandideCollectionBasic < CandideCollectionItem < CandideCollectionItemAdministrator

class CandideCollectionItemAdministrator extends CandideCollectionItem {

    use Administrator, WysiwygFiles, JsonReader;

    private $_collectionAdministrator;
    private $_fullStructure;
    private $_fullData = [];
    private $_newItem = false;

    public function __construct(String $instanceName, $id) {
        $this->_collectionAdministrator = new CandideCollectionAdministrator($instanceName);
        if ($id == 'newItem') {
            $id = $this->_collectionAdministrator->getNewId();
            $this->_newItem = true;
        }
        parent::__construct($instanceName,$id);
        $this->_structure = $this->readJsonFile($this->getStructureUrl());
        $this->_fullStructure = array_merge($this->_collectionAdministrator->_structure,$this->_structure);
        if (!$this->_newItem) {
            $this->_fullData = array_merge($this->_data,$this->_collectionAdministrator->getDataForElementWithId($this->_id));
        }
    }

    public function getTitle(AdminTextsManager $texts){
        if ($this->_newItem){
            echo $texts->get("new_item_in_collection").' "'.$this->formatTitle($this->_instanceName).'"';
        } else {
            echo $texts->get("edit_item_of_the_collection").' "'.$this->formatTitle($this->_instanceName).'"';
        }
    }
    public function getCallToActionText(AdminTextsManager $texts){
        echo ($this->_newItem) ? $texts->get("add") : $texts->get("save");
    }

    public function getFields() {
        foreach ($this->_fullStructure as $key => $value){
            $data = (key_exists($key,$this->_fullData) && key_exists('data',$this->_fullData[$key])) ? $this->_fullData[$key]['data'] : "";
            echo $this->getField($key,$data,$value);
        }
    }

    public function deleteThisItem(){
        // Supprimer les données sur l'item
        $this->deleteFiles(self::DATA_DIRECTORY.$this->_instanceName."/items/".$this->_id);
        $this->deleteFiles(self::FILES_DIRECTORY.$this->_instanceName."/".$this->_id);
        // Supprimer l'item de $this->_collectionAdministrator->_data
        $this->_collectionAdministrator->removeItem($this->_id);
    }

    public function setData(Array $texts, Array $files){
        $this->_data = array_merge($this->_structure,$this->_data);
        $newFiles = $this->setImages($files, $texts, $this->_data);
        $this->setTexts($texts);
        $this->saveData();
        $collectionData = $this->_collectionAdministrator->setData($texts,$newFiles,$this->_id);
        $this->removeWysiwygFiles(self::FILES_DIRECTORY.$this->_instanceName."/".$this->_id,array_merge($collectionData,$this->_data));
        echo $this->_id;
    }

    private function setTexts(Array $texts) {
        foreach ($this->_structure as $key => $value) {
            if (key_exists($key,$texts)) {
                $this->_data[$key]['data'] = $texts[$key];
            }
        }
    }

    private function setImages(Array $files, Array &$texts, Array $infos) : Array {
        $newFiles = [];
        foreach ($files as $key => $file) {
            $dest = $this->getInstanceName()."/".$this->_id;
            if ($file["size"] != 0 && strpos($key,"image_") === 0) {
                $data = key_exists($key,$this->_fullData) ? $this->_fullData[$key] : [];
                $url = $this->savePicture($key, $file, $dest, $data, $this->_fullStructure[$key]);
                $newFiles[$key] = $url;
                if (key_exists($key,$this->_data)) {
                    $this->_data[$key]['data'] = $url;
                }
            } else if ($file["size"] != 0) {
                $url = $this->saveWysiwygFile($key,$file,$dest."/wysiwyg",$texts,$infos);
            }
        }
        return $newFiles;
    }

}