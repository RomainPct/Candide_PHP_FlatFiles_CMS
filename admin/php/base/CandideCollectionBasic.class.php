<?php

class CandideCollectionBasic extends CandideBasic {

    protected $_structure = [];

    protected function manageStructureUpdate($name,$type,$options) {
        if ($this->_updateCall) {
            $this->_structure[$name] = ["type" => $type];
            switch($type) {
                case "image":
                    $this->_structure[$name]["width"] = $options["size"][0];
                    $this->_structure[$name]["height"] = $options["size"][1];
                    break;
                case "text":
                    $this->_structure[$name]["wysiwyg"] = $options["wysiwyg"];
                    break;
                case "number":
                    $this->_structure[$name]["format"] = $options["format"];
                    break;
            }
        }
    }

    protected function manageItemDataUpdate(&$item,$name,$type,$options){
        if ($this->_updateCall) {
            $item[$name]["type"] = $type;
            switch($type) {
                case "image":
                    $item[$name]["width"] = $options["size"][0];
                    $item[$name]["height"] = $options["size"][1];
                    break;
                case "text":
                    $item[$name]["wysiwyg"] = $options["wysiwyg"];
                    break;
                case "number":
                    $item[$name]["format"] = $options["format"];
                    break;
            }
        }
    }

    public function save() {
        if ($this->_updateCall && count($this->_structure) > 0) {
            file_put_contents($this->getStructureUrl(),json_encode($this->_structure));
            file_put_contents($this->getPageUrl(),json_encode($this->_data));
        }
    }

}