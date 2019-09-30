<?php

// Basic < CandideBasic < CandidePage

class CandidePage extends CandideBasic {

    use ElementsGetter;

    protected $_type = self::TYPE_ITEM;
    private $_existingElements = [];

    /**
     * Manage page update when called from updateAdminPlatform.php
     *
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Field custom options]
     * @return void
     */
    protected function manageUpdate(String $name, String $type, Array $options){
        if (!in_array($name,$this->_existingElements)) {
            $this->_existingElements[] = $name;
        }
        $this->_data[$name]["type"] = $type;
        $this->setDefaultValueIfNeeded($this->_data[$name]);
        foreach ($options as $key => $value) {
            $this->_data[$name][$key] = $value;
        }
    }

    /**
     * Generate default value for a new item
     *
     * @param Array $field [Field data by reference]
     * @return void
     */
    protected function setDefaultValueIfNeeded(Array &$field){
        if (!array_key_exists("data",$field)) {
            switch ($field["type"]) {
                case "text":
                    $field["data"] = "undefined";
                    break;
                case "image":
                    $field["data"] = "undefined.jpg";
                    break;
                case "number":
                    $field["data"] = "0";
                    break;
                default:
                    $field["data"] = "";
                    break;
            }
        }
    }

    /**
     * Save updates when called from updateAdminPlatform
     *
     * @return void
     */
    public function save() {
        if ($this->_updateCall) {
            $this->_data = array_filter($this->_data, function($key) {
                return in_array($key,$this->_existingElements);
            },ARRAY_FILTER_USE_KEY);
            $this->saveData();
        }
    }
}