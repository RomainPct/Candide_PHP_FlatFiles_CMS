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

    public function text($title,$wysiwyg = false){
        $this->getElement($title,"text",[],$wysiwyg);
    }

    public function image($title,$size){
        $this->getElement($title,"image",$size);
    }

    protected function getElement($title,$prefix,$size = [],$wysiwyg = false) {
        $name = $prefix."_".$title;
        // Gérer l'update
        if ($this->_updateCall) {
            $this->_structure[$name] = ["type" => $prefix];
            if ($prefix == "image") {
                $this->_structure[$name]["width"] = $size[0];
                $this->_structure[$name]["height"] = $size[1];
            } else if ($prefix == "text") {
                $this->_structure[$name]["wysiwyg"] = $wysiwyg;
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
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
        }
    }

}