<?php

class CandideCollection extends CandideCollectionBasic {

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
        $this->getElement($title,$index,"text",["wysiwyg"=>$wysiwyg]);
    }

    public function image($title,$index,$size){
        $this->getElement($title,$index,"image",["size"=>$size]);
    }

    public function number($title,$index,$format = NumberFormatter::DECIMAL){
        $this->getElement($title,$index,"number",["format"=>$format]);
    }

    protected function getElement($title,$index,$type,$options) {
        $name = $type."_".$title;
        // Gérer l'update
        $this->manageStructureUpdate($name,$type,$options);
        // Gérer l'affichage
        if (array_key_exists($index,$this->_data) && array_key_exists($name,$this->_data[$index]) && array_key_exists("data",$this->_data[$index][$name])) {
            echo $this->formatElement($this->_data[$index][$name]);
        } else {
            echo "update candide on the admin platform";
        }
    }

    public function save() {
        if ($this->_updateCall && count($this->_structure) > 0) {
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
        }
    }

}