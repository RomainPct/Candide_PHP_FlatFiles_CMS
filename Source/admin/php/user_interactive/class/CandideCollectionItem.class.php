<?php
/**
 * CandideCollectionItem.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Client side class to create a collection item page
 * 
 * @since 1.0
 * Basic < CandideBasic < CandideCollectionBasic < CandideCollectionItem
 * 
 */
class CandideCollectionItem extends CandideCollectionBasic {

    use ElementGetters;

    protected $_id;
    protected $_type = self::TYPE_ITEM;

    /**
     * Custom CandideCollectionItem constructor
     *
     * @param String $instanceName [Parent collection name]
     * @param Int|null $id [Item id]
     * @param String[] $extensions [Extensions needed for this instance]
     */
    public function __construct(String $instanceName,?Int $id, Array $extensions = []) {
        $this->_id = intval($id);
        parent::__construct($instanceName, $extensions);
    }

    /**
     * Return base.json path for the current collection item from ROOT_DIR
     *
     * @return String [Path of the base.json]
     */
    protected function getInstanceUrl():String {
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_instanceName."/items/".$this->_id."/base.json");
    }

    /**
     * Return detailedStructure.json path for the current collection item from ROOT_DIR
     *
     * @return String [Path of the detailedStructure.json]
     */
    protected function getStructureUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_instanceName."/structure/detailedStructure.json");
    }

    /**
     * Return item id
     *
     * @return Int [Item id]
     */
    public function getId():Int {
        return $this->_id;
    }

    /**
     * Manage collection item update
     *
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Field custom options]
     * @return void
     */
    protected function manageUpdate(String $name, String $type, Array $options){
        $this->manageStructureUpdate($name,$type,$options);
        $this->manageFieldInfosUpdate($this->_data,$name,$type,$options);
    }

    /**
     * Merge current instance with another one
     *
     * @param CandideCollectionItem $c [Candide collection item instance to merge with]
     * @return void
     */
    public function mergeWith(CandideCollectionItem $c) {
        foreach ($c->_data as $key => $data) if (!key_exists($key, $this->_data) && key_exists('type', $data)) {
            $type = $data["type"];
            unset($data["type"]);
            unset($data["data"]);
            $this->manageUpdate($key, $type, $data);
        }
    }

}