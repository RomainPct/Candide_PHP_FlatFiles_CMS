<?php

// Basic < CandideBasic < CandideCollectionBasic

class CandideCollectionBasic extends CandideBasic {

    protected $_structure = [];

    protected function manageStructureUpdate($name,$type,$options) {
        $this->_structure[$name] = ["type" => $type];
            foreach ($options as $key => $value) {
                $this->_structure[$name][$key] = $value;
            }
    }

    protected function manageItemDataUpdate(&$item,$name,$type,$options){
        $item[$name]["type"] = $type;
        if (!key_exists("data",$item[$name])) {
            $item[$name]["data"] = "";
        }
        foreach ($options as $key => $value) {
            $item[$name][$key] = $value;
        }
    }

    public function save() {
        if ($this->_updateCall && count($this->_structure) > 0) {
            $this->cleanData();
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
            file_put_contents($this->getPageUrl(),json_encode($this->_data));
        }
    }

}