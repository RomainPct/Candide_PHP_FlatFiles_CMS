<?php

class CandidePage extends CandideBasic {

    private $_calledElements = [];
    private $_newElementAdded = false;

    public function text($title){
        $this->getElement($title,"text");
    }

    public function image($title, $size){
        $this->getElement($title,"image",$size);

    }

    protected function getElement($title, $type, $size = []) {
        $name = $type."_".$title;
        if (!in_array($name,$this->_calledElements)) {
            $this->_calledElements[] = $name;
        }
        $this->_data[$name]["type"] = $type;
        if (!array_key_exists($name,$this->_data) || !array_key_exists("data",$this->_data[$name])) {
            if ($type == "image") {
                $this->_data[$name]["data"] = "default.jpg";
            } else {
                $this->_data[$name]["data"] = "default_text";
            }
            $this->_newElementAdded = true;
        }
        if ($type == "image") {
            $this->_data[$name]["width"] = $size[0];
            $this->_data[$name]["height"] = $size[1];
            $this->_newElementAdded = true;
        }
        echo $this->formatText($this->_data[$name]["data"]);
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