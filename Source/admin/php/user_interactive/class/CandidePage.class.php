<?php

class CandidePage extends CandideBasic {

    use ElementsGetter;

    private $_existingElements = [];

    protected function manageUpdate($name,$type,$options){
        if ($this->_updateCall) {
            if (!in_array($name,$this->_existingElements)) {
                $this->_existingElements[] = $name;
            }
            $this->_data[$name]["type"] = $type;
            $this->setDefaultValueIfNeeded($name,$type);
            foreach ($options as $key => $value) {
                $this->_data[$name][$key] = $value;
            }
        }
    }

    protected function setDefaultValueIfNeeded($name,$type){
        if (!array_key_exists("data",$this->_data[$name])) {
            switch ($type) {
                case "text":
                    $this->_data[$name]["data"] = "undefined";
                    break;
                case "image":
                    $this->_data[$name]["data"] = "undefined.jpg";
                    break;
                case "number":
                    $this->_data[$name]["data"] = "0";
                    break;
                default:
                    $this->_data[$name]["data"] = "";
                    break;
            }
        }
    }

    public function save() {
        $this->_data = array_filter($this->_data, function($key) {
            return in_array($key,$this->_existingElements);
        },ARRAY_FILTER_USE_KEY);
        $this->saveData();
    }
}