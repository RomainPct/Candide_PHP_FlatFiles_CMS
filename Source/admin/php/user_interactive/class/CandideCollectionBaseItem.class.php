<?php
/**
 * CandideCollectionBaseItem.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Client side class used by CandideCollection to provide user a better interface to display an item
 * 
 * @since 1.0
 * Basic < CandideCollectionBaseItem
 * 
 */
class CandideCollectionBaseItem extends Basic {

    use ElementGetters;

    protected $_type = self::TYPE_ITEM;

    /**
     * CandideCollectionBaseItem constructor
     *
     * @param Array $data [Item specific data]
     * @param String[] $extensions [Extensions needed for this instance]
     */
    public function __construct(Array $data, Array $extensions) {
        $this->_data = $data;
        parent::__construct($extensions);
    }

    /**
     * Return the item id
     *
     * @return String
     */
    public function getId():String {
        return $this->_data["id"];
    }

    /**
     * Add callback to update collection
     *
     * @param Callable $updateCall [Update callback]
     * @return void
     */
    public function makeReadyForUpdateCall(Callable $updateCall) {
        $this->addCallable("manageUpdate",$updateCall);
    }

}