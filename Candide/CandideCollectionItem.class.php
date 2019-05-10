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

    public function image($title,$size){
        $this->getElement($title,"image",$size);
    }

    protected function getElement($title,$prefix,$size = []) {
        $name = $prefix."_".$title;
        if (!in_array($name,$this->_structure)) {
            $this->_structure[$name] = ["type" => $prefix];
            if ($prefix == "image") {
                $this->_structure[$name]["width"] = $size[0];
                $this->_structure[$name]["height"] = $size[1];
            }
        }
        if (array_key_exists($name,$this->_data) && array_key_exists("data",$this->_data[$name])) {
            echo $this->formatText($this->_data[$name]["data"]);
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