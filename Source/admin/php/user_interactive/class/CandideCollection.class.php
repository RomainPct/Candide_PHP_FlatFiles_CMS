<?php

// Basic < CandideBasic < CandideCollectionBasic < CandideCollection

class CandideCollection extends CandideCollectionBasic {

    use IndexedElementsGetter;

    protected $_type = self::TYPE_COLLECTION;

    /**
     * Return the path of the base.json file for the current page from ROOT_DIR
     *
     * @return String [Path of the base.json]
     */
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

    /**
     * Manage structure and in data update for each element of the collection
     *
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Array of options]
     * @return void
     */
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