<?php
/**
 * CandideCollectionBaseItemAdministrator.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Specific CandideCollectionBaseItem for admin side without plugin managment but title property managment
 * 
 * @since 1.0
 * Basic < CandideCollectionBaseItem < CandideCollectionBaseItemAdministrator
 * 
 */

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
            if (
                $this->_data[$key]["type"] == "text"
                && !($this->_data[$key]["wysiwyg"] ?? false)
                && key_exists("data",$this->_data[$key])
                && $this->_data[$key]["data"] != "" ) {
                echo htmlentities(substr($this->_data[$key]["data"],0,100));
                return;
            }
        }
        echo "Sans nom {$this->_data["id"]}";
    }

}