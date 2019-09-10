<?php

class CandideCollectionBasic extends CandideBasic {

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

}