<?php

class CandideCollection extends CandideBasic {

    protected $_structure = [];

    protected function getPageUrl() {
        if (!file_exists(self::DATA_DIRECTORY.$this->_page."/global")){
            mkdir(self::DATA_DIRECTORY.$this->_page."/global",0777,true);
        }
        return self::DATA_DIRECTORY.$this->_page."/global/base.json";
    }

    protected function getStructureUrl(){
        if (!file_exists(self::DATA_DIRECTORY.$this->_page."/structure/")){
            mkdir(self::DATA_DIRECTORY.$this->_page."/structure/",0777,true);
        }
        return self::DATA_DIRECTORY.$this->_page."/structure/globalStructure.json";
    }

    public function avalaibleItemIds():Array {
        if ($this->_updateCall) {
            return [0];
        } else {
            return array_reverse(array_keys($this->_data));
        }
    }

    public function text($title,$index,$wysiwyg = false){
        $this->getElement($title,$index,"text",[],$wysiwyg);
    }

    public function image($title,$index,$size){
        $this->getElement($title,$index,"image",$size);
    }

    protected function getElement($title,$index,$prefix,$size = [],$wysiwyg = false) {
        $name = $prefix."_".$title;
        // Gérer l'update
        if ($this->_updateCall) {
            $this->_structure[$name] = ["type" => $prefix];
            if ($prefix == "image") {
                $this->_structure[$name]["width"] = $size[0];
                $this->_structure[$name]["height"] = $size[1];
            } else if ($prefix == "text"){
                $this->_structure[$name]["wysiwyg"] = $wysiwyg;
            }   
        }
        // Gérer l'affichage
        if (array_key_exists($index,$this->_data) && array_key_exists($name,$this->_data[$index])) {
            echo $this->formatText($this->_data[$index][$name]["data"]);
        } else {
            echo "update candide on the admin platform";
        }
    }

    public function end() {
        if ($this->_updateCall && count($this->_structure) > 0) {
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
        }
    }

}