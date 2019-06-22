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
        foreach (json_decode($texts["trixFilesToDelete"]) as $file){
            $this->deleteFiles("../".$file);
        }
    }

    private function setTexts(Array $texts) {
        foreach ($texts as $key => $text){
            if (substr( $key, 0, 5 ) === "text_") {
                $this->_data[$key]['data'] = $text;
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