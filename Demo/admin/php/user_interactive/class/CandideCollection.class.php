<?php
/**
 * CandideCollection.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Client side class to create a collection page
 * 
 * @since 1.0
 * Basic < CandideBasic < CandideCollectionBasic < CandideCollection
 * 
 */
class CandideCollection extends CandideCollectionBasic {

    private $_extensions;

    /**
     * CandideCollection constructor
     *
     * @param String $instanceName [Collection name]
     * @param String[] $extensions [Extensions needed for this specific instance]
     */
    public function __construct(String $instanceName, Array $extensions = []) {
        parent::__construct($instanceName, []);
        $this->_extensions = $extensions;
    }

    /**
     * Return base.json path for the current collection from ROOT_DIR
     *
     * @return String [Path of the base.json]
     */
    protected function getInstanceUrl():String {
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_instanceName."/global/base.json");
    }

    /**
     * Return globalStructure.json path for the current collection from ROOT_DIR
     *
     * @return String
     */
    protected function getStructureUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_instanceName."/structure/globalStructure.json");
    }

    /**
     * Return all CandideCollectionBaseItem for the current collection
     *
     * @return CandideCollectionBaseItem[] [An array of CandideCollectionBaseItem]
     */
    public function items():Array {
        if ($this->_updateCall) {
            $item = new CandideCollectionBaseItem(["id" => 0],$this->_extensions);
            $item->makeReadyForUpdateCall(function(String $name, String $type, Array $options){
                $this->manageUpdate($name,$type,$options);
            });
            return [$item];
        } else {
            $items = array_map(function($item){
                return new CandideCollectionBaseItem($item,$this->_extensions);
            },$this->_data);
            return $items;
        }
    }

    /**
     * Manage collection update
     *
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Field custom options]
     * @return void
     */
    protected function manageUpdate(String $name, String $type, Array $options){
        $this->manageStructureUpdate($name,$type,$options);
        $this->manageDataUpdate($name,$type,$options);
    }

    /**
     * Manage data update each global item
     *
     * @param String $name
     * @param String $type
     * @param Array $options
     * @return void
     */
    protected function manageDataUpdate(String $name, String $type, Array $options){
        if (count($this->_data) > 0) {
            foreach($this->_data as &$item) {
                $this->manageFieldInfosUpdate($item,$name,$type,$options);
            }
        }
    }

}