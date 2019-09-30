<?php
class CandideCollectionBaseItem extends Basic {

    protected $_type = self::TYPE_ITEM;

    use ElementsGetter;

    public function __construct(Array $data, Array $extensions) {
        $this->_data = $data;
        parent::__construct($extensions);
    }

    public function id() {
        echo $this->_data["id"];
    }

    public function makeReadyForUpdateCall(Callable $updateCall) {
        $this->addMethod("manageUpdate",$updateCall);
    }

}