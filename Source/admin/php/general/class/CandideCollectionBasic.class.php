<?php

// Basic < CandideBasic < CandideCollectionBasic

class CandideCollectionBasic extends CandideBasic {

    protected $_structure = [];

    /**
     * Manage collection structure update
     *
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Options]
     * @return void
     */
    protected function manageStructureUpdate(String $name, String $type, Array $options) {
        $this->_structure[$name] = ["type" => $type];
        foreach ($options as $key => $value) {
            $this->_structure[$name][$key] = $value;
        }
    }

    /**
     * Manage collection data update
     *
     * @param Array $item [Item data by reference]
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Options]
     * @return void
     */
    protected function manageItemDataUpdate(Array &$item,String $name, String $type, Array $options){
        $item[$name]["type"] = $type;
        if (!key_exists("data",$item[$name])) {
            $item[$name]["data"] = "";
        }
        foreach ($options as $key => $value) {
            $item[$name][$key] = $value;
        }
    }

    /**
     * Save updates when called from updateAdminPlatform.php
     *
     * @return void
     */
    public function save() {
        if ($this->_updateCall && count($this->_structure) > 0) {
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
            file_put_contents($this->getPageUrl(),json_encode($this->_data));
        }
    }

}