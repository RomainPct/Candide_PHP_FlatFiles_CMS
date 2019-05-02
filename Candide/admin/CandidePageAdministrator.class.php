<?php

class CandidePageAdministrator extends CandideBasic {

    use Administrator;

    public function getFields() {
        foreach ($this->_data as $key => $value){
            // Afficher input type text ou image
            echo $this->getField($key,$value['type'],$value['data']);
        }
    }

    public function setData(Array $texts, Array $files) {
        $this->setTexts($texts);
        $this->setImages($files);
        $this->saveData();
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
                if (!file_exists(self::FILES_DIRECTORY.$this->getPage())) {
                    mkdir(self::FILES_DIRECTORY.$this->getPage(),0777,true);
                }
                // Enregistrer l'image dans un dossier
                if (move_uploaded_file($file["tmp_name"],self::FILES_DIRECTORY.$this->getPage()."/".$file["name"])){
                    // Editer l'url de l'image
                    $this->_data[$key]['data'] = "/CandideData/files/".$this->getPage()."/".$file["name"];
                }
            }
        }
    }

}