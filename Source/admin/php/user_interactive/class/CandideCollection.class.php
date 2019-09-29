<?php

// Basic < CandideBasic < CandideCollectionBasic < CandideCollection

class CandideCollection extends CandideCollectionBasic {

    /**
     * Return base.json path for the current collection from ROOT_DIR
     *
     * @return String [Path of the base.json]
     */
    protected function getPageUrl():String {
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/global/base.json");
    }

    /**
     * Return globalStructure.json path for the current collection from ROOT_DIR
     *
     * @return String
     */
    protected function getStructureUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/structure/globalStructure.json");
    }

    /**
     * Return all CandideCollectionBaseItem for the current collection
     *
     * @return CandideCollectionBaseItem[] [An array of CandideCollectionBaseItem]
     */
    public function items():Array {
        if ($this->_updateCall) {
            $item = new CandideCollectionBaseItem(["id" => 0]);
            $item->makeReadyForUpdateCall(function(String $name, String $type, Array $options){
                $this->manageUpdate($name,$type,$options);
            });
            return [$item];
        } else {
            $items = array_map(function($item){
                return new CandideCollectionBaseItem($item);
            },$this->_data);
            return $items;
        }
    }

    protected function manageUpdate(String $name, String $type, Array $options){
        $this->manageStructureUpdate($name,$type,$options);
        $this->manageDataUpdate($name,$type,$options);
    }

    /**
     * Manage data update for CandideCollectionBaseItem
     *
     * @param String $name
     * @param String $type
     * @param Array $options
     * @return void
     */
    protected function manageDataUpdate(String $name, String $type, Array $options){
        if (count($this->_data) > 0) {
            foreach($this->_data as &$item) {
                $this->manageItemDataUpdate($item,$name,$type,$options);
            }
        }
    }

}