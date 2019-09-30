<?php

// Basic < CandideBasic < CandideCollectionBasic < CandideCollectionItem

class CandideCollectionItem extends CandideCollectionBasic {

    use ElementsGetter;

    protected $_id;
    protected $_type = self::TYPE_ITEM;

    /**
     * Custom CandideCollectionItem constructor
     *
     * @param String $page [Parent collection name]
     * @param Int|null $id [Item id]
     * @param String[] $extensions [Extensions needed for this instance]
     */
    public function __construct(String $page,?Int $id, Array $extensions = []) {
        $this->_id = intval($id);
        parent::__construct($page, $extensions);
    }

    /**
     * Return base.json path for the current collection item from ROOT_DIR
     *
     * @return String [Path of the base.json]
     */
    protected function getPageUrl():String {
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/items/".$this->_id."/base.json");
    }

    /**
     * Return detailedStructure.json path for the current collection item from ROOT_DIR
     *
     * @return String [Path of the detailedStructure.json]
     */
    protected function getStructureUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/structure/detailedStructure.json");
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
        $this->manageItemDataUpdate($this->_data,$name,$type,$options);
    }

}