<?php


class CandideCollectionItem extends CandideCollectionBasic {

    use ElementsGetter;

    protected $_id;

    protected function getPageUrl():String {
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/items/".$this->_id."/base.json");
    }

    protected function getStructureUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/structure/detailedStructure.json");
    }

    public function __construct(String $page, $id) {
        $this->_id = intval($id);
        parent::__construct($page);
    }

    public function getId():Int {
        return $this->_id;
    }

    protected function manageUpdate($name,$type,$options){
        $this->manageStructureUpdate($name,$type,$options);
        $this->manageItemDataUpdate($this->_data,$name,$type,$options);
    }

}