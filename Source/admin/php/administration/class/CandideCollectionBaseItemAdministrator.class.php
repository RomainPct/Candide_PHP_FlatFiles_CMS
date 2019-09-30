<?php

class CandideCollectionBaseItemAdministrator extends CandideCollectionBaseItem {

    private $_structure;

    /**
     * CandideCollectionBaseItemAdministrator constructor which set _data & _structure
     *
     * @param Array $data [Base item data]
     * @param Array $structure [Base item structure]
     */
    public function __construct(Array $data, Array $structure) {
        $this->_structure = $structure;
        parent::__construct($data,[]);
    }

    /**
     * Echo element title
     *
     * @return void
     */
    public function getElementTitle(){
        for ($i=0; $i < count($this->_structure); $i++) { 
            $key = array_keys($this->_structure)[$i];
            if ($this->_data[$key]["type"] == "text" && key_exists("data",$this->_data[$key]) && $this->_data[$key]["data"] != ""){
                echo substr($this->_data[$key]["data"],0,100);
                return;
            }
        }
        echo "Sans nom {$this->_data["id"]}";
    }

}