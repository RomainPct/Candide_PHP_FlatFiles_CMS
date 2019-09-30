<?php

// Basic < CandideBasic < CandideCollectionBasic < CandideCollection < CandideCollectionAdministrator

class CandideCollectionAdministrator extends CandideCollection {

    public function __construct(String $instanceName){
        parent::__construct($instanceName);
        $this->_structure = $this->readJsonFile($this->getStructureUrl());
    }

    public function getNewId():Int {
        if (count($this->_data) == 0) {
            return 0;
        } else {
            return intval(end($this->_data)["id"]) + 1;
        }
    }

    public function removeItem(Int $id){
        unset($this->_data[$id]);
        $this->saveData();
    }

    public function getDataForElementWithId(Int $id):Array{
        return $this->_data[$id];
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

    public function setData(Array $texts, Array $files, Int $id){
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

    private function setTexts(Array $texts, Int $id) {
        foreach ($this->_structure as $key => $value) {
            if (key_exists($key,$texts)) {
                $this->_data[$id][$key]['data'] = $texts[$key];
            }
        }
    }

    private function setImages(Array $newFiles, Int $id){
        foreach ($newFiles as $key => $url) {
            $this->_data[$id][$key]['data'] = $url;
        }
    }

}