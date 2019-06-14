<?php

class CandidePage extends CandideBasic {

    private $_calledElements = [];
    private $_newElementAdded = false;

    public function text($title,$wysiwyg = false){
        $this->getElement($title,"text",[],$wysiwyg);
    }

    public function image($title, $size){
        $this->getElement($title,"image",$size);

    }

    protected function getElement($title, $type, $size = [], $wysiwyg = false) {
        $name = $type."_".$title;
        // Gérer l'update
        if ($this->_updateCall) {
            if (!in_array($name,$this->_calledElements)) {
                $this->_calledElements[] = $name;
            }
            $this->_data[$name]["type"] = $type;
            if (!array_key_exists($name,$this->_data) || !array_key_exists("data",$this->_data[$name])) {
                $this->_data[$name]["data"] = "undefined";
                $this->_newElementAdded = true;
            }
            if ($type == "image") {
                $this->_data[$name]["width"] = $size[0];
                $this->_data[$name]["height"] = $size[1];
                $this->_newElementAdded = true;
            } else if ($type=="text") {
                $this->_data[$name]["wysiwyg"] = $wysiwyg;
            }   
        }
        // Gérer l'affichage
        if (array_key_exists($name,$this->_data) && array_key_exists("data",$this->_data[$name])) {
            echo $this->formatText($this->_data[$name]["data"]);
        } else {
            echo "update candide on the admin platform";
        }
    }

    public function end() {
        if ($this->_updateCall) {
            if (count($this->_data) != count($this->_calledElements) || $this->_newElementAdded) {
                $oldData = $this->_data;
                $this->_data = [];
                foreach ($this->_calledElements as $key => $element) {
                    $this->_data[$element] = $oldData[$element];
                }
                $this->saveData();
            }
        }
    }
}