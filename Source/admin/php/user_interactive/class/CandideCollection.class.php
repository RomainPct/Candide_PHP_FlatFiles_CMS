<?php

class CandideCollection extends CandideCollectionBasic {

    use IndexedElementsGetter;

    protected function getPageUrl():String {
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/global/base.json");
    }

    protected function getStructureUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/structure/globalStructure.json");
    }

    public function avalaibleItemIds():Array {
        if ($this->_updateCall) {
            return [0];
        } else {
            return array_reverse(array_keys($this->_data));
        }
    }

    protected function manageUpdate(String $name, String $type, Array $options){
        $this->manageStructureUpdate($name,$type,$options);
        $this->manageDataUpdate($name,$type,$options);
    }

    protected function manageDataUpdate(String $name, String $type, Array $options){
        if ($this->_updateCall && count($this->_data) > 0) {
            foreach($this->_data as &$item) {
                $this->manageItemDataUpdate($item,$name,$type,$options);
            }
        }
    }

}