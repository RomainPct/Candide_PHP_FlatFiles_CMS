<?php


class CandideCollectionItem extends CandideCollectionBasic {

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
        $this->getElement($title,"text",["wysiwyg"=>$wysiwyg]);
    }

    public function image($title,$size){
        $this->getElement($title,"image",["size"=>$size]);
    }

    protected function getElement($title,$type,$options) {
        $name = $type."_".$title;
        // Gérer l'update
        $this->manageStructureUpdate($name,$type,$options);
        // Gérer l'affichage
        if (array_key_exists($name,$this->_data) && array_key_exists("data",$this->_data[$name])) {
            echo $this->formatElement($this->_data[$name]);
        } else {
            echo "update candide on the admin platform";
        }
    }

    public function save() {
        if ($this->_updateCall) {
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
        }
    }

}