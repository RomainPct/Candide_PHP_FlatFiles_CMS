<?php

class CandidePage extends CandideBasic {

    private $_calledElements = [];
    private $_newElementAdded = false;

    public function text($title){
        $this->getElement($title,"text");
    }

    public function image($title){
        $this->getElement($title,"image");
    }

    protected function getElement($title, $type) {
        $name = $type."_".$title;
        if (!in_array($name,$this->_calledElements)) {
            $this->_calledElements[] = $name;
        }
        if (!array_key_exists($name,$this->_data)) {
            if ($type == "image") {
                $this->_data[$name]["data"] = "default.jpg";
            } else {
                $this->_data[$name]["data"] = "default_text";
            }
            $this->_data[$name]["type"] = $type;
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