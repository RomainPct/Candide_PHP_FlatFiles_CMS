<?php


class CandideCollectionAdministrator extends CandideCollection {

    public function getNewId():Int {
        if (count($this->_data) == 0) {
            return 0;
        } else {
            return intval(end($this->_data)["id"]) + 1;
        }
    }

    public function getStructure():Array {
        if (file_exists($this->getStructureUrl())) {
            $this->_structure = json_decode(file_get_contents($this->getStructureUrl()),true);
        }
        return $this->_structure;
    }

    public function removeItem(Int $id){
        unset($this->_data[$id]);
        $this->saveData();
    }

    public function getDataForElementWithId(Int $id):Array{
        return $this->_data[$id];
    }

    public function getElementTitle($id){
        $secondKey = array_keys($this->_data[$id])[0];
        echo substr($this->_data[$id][$secondKey]['data'],0,100);
    }

    public function setData(Array $texts, Array $files, Int $id){
        if (!key_exists($id,$this->_data)) {
            $this->_data[$id] = [];
            $this->_data[$id]["id"] = $id;
        }
        //var_dump($this->_data[$id]);
        $this->_data[$id] = array_merge($this->_structure,$this->_data[$id]);
        //var_dump($this->_data[$id]);
        $this->setTexts($texts,$id);
        $this->setImages($files,$id);
        //var_dump($this->_data[$id]);
        $this->saveData();
    }

    private function setTexts(Array $texts, Int $id) {
        foreach ($this->_structure as $key => $value) {
            if (key_exists($key,$texts)) {
                $this->_data[$id][$key]['data'] = $texts[$key];
            }
        }
    }

    private function setImages(Array $files, Int $id){
        foreach ($files as $key => $file) {
            if ($file["size"] != 0) {
                // Editer l'url de l'image
                //echo $key." collection edited";
                $this->_data[$id][$key]['data'] = "/CandideData/files/".$this->getPage()."/".$id."/".$key."_".$file["name"];
            }
        }
    }

}