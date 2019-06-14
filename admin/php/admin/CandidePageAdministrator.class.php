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
                // Créer le dossier
                if (!file_exists(self::FILES_DIRECTORY.$this->getPage())) {
                    mkdir(self::FILES_DIRECTORY.$this->getPage(),0777,true);
                }
                $name = $key."_".time().$file["name"];
                // Resize de l'image
                $img = $this->resize($file["tmp_name"],$this->_data[$key]['width'],$this->_data[$key]['height']);
                // Enregistrer l'image dans un dossier
                imagejpeg($img, self::FILES_DIRECTORY.$this->getPage()."/".$name, 100);
                // Editer l'url de l'image
                $this->_data[$key]['data'] = "/CandideData/files/".$this->getPage()."/".$name;
            }
        }
    }

}