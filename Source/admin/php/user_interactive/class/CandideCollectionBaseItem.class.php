<?php
class CandideCollectionBaseItem extends Basic {

    use ElementsGetter;

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
     * Echo the item id
     *
     * @return void
     */
    public function id() {
        echo $this->_data["id"];
    }

    /**
     * Add callback for updateAdminPlatform.php
     *
     * @param Callable $updateCall [Update callback]
     * @return void
     */
    public function makeReadyForUpdateCall(Callable $updateCall) {
        $this->addMethod("manageUpdate",$updateCall);
    }

}