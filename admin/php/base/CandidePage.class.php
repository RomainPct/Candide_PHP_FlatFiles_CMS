<?php

class CandidePage extends CandideBasic {

    private $_existingElements = [];

    public function text($title,$wysiwyg = false){
        $this->getElement($title,"text",["wysiwyg" => $wysiwyg]);
    }

    public function image($title, $size){
        $this->getElement($title,"image",["size" => $size]);
    }

    public function number($title,$format = NumberFormatter::DECIMAL) {
        $this->getElement($title,"number",["format" => $format]);
    }

    protected function getElement($title, $type, $options) {
        $name = $type."_".$title;
        // Gérer l'update
        $this->manageUpdate($name,$type,$options);
        // Gérer l'affichage
        if (array_key_exists($name,$this->_data) && array_key_exists("data",$this->_data[$name])) {
            echo $this->formatElement($this->_data[$name]);
        } else {
            echo "Update candide on the admin platform";
        }
    }

    protected function manageUpdate($name,$type,$options){
        if ($this->_updateCall) {
            if (!in_array($name,$this->_existingElements)) {
                $this->_existingElements[] = $name;
            }
            $this->_data[$name]["type"] = $type;
            $this->setDefaultValueIfNeeded($name,$type);
            switch ($type) {
                case "image":
                    $this->_data[$name]["width"] = $options["size"][0];
                    $this->_data[$name]["height"] = $options["size"][1];
                    break;
                case "text":
                    $this->_data[$name]["wysiwyg"] = $options["wysiwyg"];
                    break;
                case "number":
                    $this->_data[$name]["format"] = $options["format"];
                    break;
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