<?php

// Basic < CandideBasic < CandidePageAdministrator

class CandidePageAdministrator extends CandideBasic {

    use Administrator, WysiwygFiles;

    public function getFields() {
        foreach ($this->_data as $key => $value){
            // Afficher input type text ou image
            echo $this->getField($key,$value['data'],$value);
        }
    }

    public function setData(Array $texts, Array $files) {
        $this->setImages($files, $texts, $this->_data);
        $this->setTexts($texts);
        $this->saveData();
        $this->cleanWysiwygFiles(self::FILES_DIRECTORY.$this->getInstanceName());
    }

    private function setTexts(Array $texts) {
        foreach ($texts as $key => $text){
            if (key_exists($key,$this->_data)) {
                if ($this->_data[$key]['type'] != "image") {
                    $this->_data[$key]['data'] = $text;
                }
            }
        }
    }

    private function setImages(Array $files, Array &$texts, Array $infos){
        foreach ($files as $fieldName => $file) {
            if ($file["size"] != 0 && strpos($fieldName,"image_") === 0) {
                $this->_data[$fieldName]['data'] = $this->savePicture($fieldName,$file,$this->getInstanceName(),$this->_data[$fieldName]);
            } else if ($file["size"] != 0) {
                $url = $this->saveWysiwygFile($fieldName,$file,$this->getInstanceName()."/wysiwyg",$texts,$infos);
            }
        }
    }

}