<?php
/**
 * CandidePage.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Client side class to create a Candide Page
 * 
 * @since 1.0
 * Basic < CandideBasic < CandidePage
 * 
 */
class CandidePage extends CandideBasic {

    use ElementGetters;

    protected $_type = self::TYPE_ITEM;
    private $_existingElements = [];

    /**
     * Manage page update
     *
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Field custom options]
     * @return void
     */
    protected function manageUpdate(String $name, String $type, Array $options){
        if (!in_array($name,$this->_existingElements)) {
            $this->_existingElements[] = $name;
        }
        $this->_data[$name]["type"] = $type;
        $this->setDefaultValueIfNeeded($this->_data[$name]);
        foreach ($options as $key => $value) {
            $this->_data[$name][$key] = $value;
        }
    }

    /**
     * Generate default value for a new item
     *
     * @param Array $field [Field data by reference]
     * @return void
     */
    protected function setDefaultValueIfNeeded(Array &$field){
        if (!array_key_exists("data",$field)) {
            switch ($field["type"]) {
                case "text":
                    $field["data"] = "undefined";
                    break;
                case "image":
                    $field["data"] = "undefined.jpg";
                    break;
                case "number":
                    $field["data"] = "0";
                    break;
                default:
                    $field["data"] = "";
                    break;
            }
        }
    }

    /**
     * Save updates when called from updateAdminPlatform
     *
     * @return void
     */
    public function save() {
        if ($this->_updateCall) {
            $organizedData = [];
            foreach ($this->_existingElements as $key) {
                $organizedData[$key] = $this->_data[$key];
            }
            $this->_data = $organizedData;
            $this->saveData();
        }
    }
}