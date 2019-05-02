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
            return array_reverse(array_map(array($this, 'mapToIdArray'),$this->_data));
        }
    }
    private function mapToIdArray($array) {
        return $array["id"];
    }

    public function text($title,$index){
        $this->getElement($title,$index,"text");
    }

    public function image($title,$index){
        $this->getElement($title,$index,"image");
    }

    protected function getElement($title,$index,$prefix) {
        $name = $prefix."_".$title;
        if (!in_array($name,$this->_structure)) {
            $this->_structure[$name] = ["type" => $prefix];
        }
        if (array_key_exists($index,$this->_data) && array_key_exists($name,$this->_data[$index])) {
            echo $this->_data[$index][$name]["data"];
        } else {
            echo "undefined";
        }
    }

    public function end() {
        if ($this->_updateCall && count($this->_structure) > 0) {
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
        }
    }

}