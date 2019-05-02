<?php


class CandideCollectionItem extends CandideBasic {

    protected $_structure = [];
    protected $_id;

    protected function getPageUrl() {
        if ($this->_updateCall && !file_exists(self::DATA_DIRECTORY.$this->_page."/items/".$this->_id."/")) {
            mkdir(self::DATA_DIRECTORY.$this->_page."/items/".$this->_id."/",0777,true);
        }
        return self::DATA_DIRECTORY.$this->_page."/items/".$this->_id."/base.json";
    }

    protected function getStructureUrl(){
        if (!file_exists(self::DATA_DIRECTORY.$this->_page."/structure/")){
            mkdir(self::DATA_DIRECTORY.$this->_page."/structure/",0777,true);
        }
        return self::DATA_DIRECTORY.$this->_page."/structure/detailedStructure.json";
    }

    public function __construct(String $page, $id) {
        $this->_id = intval($id);
        parent::__construct($page);
    }

    public function getId():Int {
        return $this->_id;
    }

    public function text($title){
        $this->getElement($title,"text");
    }

    public function image($title){
        $this->getElement($title,"image");
    }

    protected function getElement($title,$prefix) {
        $name = $prefix."_".$title;
        if (!in_array($name,$this->_structure)) {
            $this->_structure[$name] = ["type" => $prefix];
        }
        if (array_key_exists($name,$this->_data)) {
            echo $this->_data[$name]["data"];
        } else {
            echo "";
        }
    }

    public function end() {
        if ($this->_updateCall) {
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
        }
    }

}