<?php

class CandidePageAdministrator extends CandideBasic {

    use Administrator;

    public function getFields() {
        foreach ($this->_data as $key => $value){
            // Afficher input type text ou image
            echo $this->getField($key,$value['type'],$value['data'],$value);
        }
    }

    public function setData(Array $texts, Array $files) {
        $this->setTexts($texts);
        $this->setImages($files);
        $this->saveData();
        $this->removeWysiwygFiles($texts["wysiwygFilesToDelete"]);
    }

    private function setTexts(Array $texts) {
        foreach ($texts as $key => $text){
            if (key_exists($key,$this->_data)) {
                if ($this->_data[$key]['type'] == "text" || $this->_data[$key]['type'] == "number") {
                    $this->_data[$key]['data'] = $text;
                }
            }
        }
    }

    private function setImages(Array $files){
        foreach ($files as $key => $file) {
            if ($file["size"] != 0) {
                $this->_data[$key]['data'] = $this->savePicture($key,$file,$this->getPage(),$this->_data[$key]);
            }
        }
    }

}