<?php

trait IndexedElementsGetter {

    public function text($title,$index,$wysiwyg = false){
        $this->getElement($title,$index,"text",["wysiwyg"=>$wysiwyg]);
    }

    public function image($title,$index,$size){
        $this->getElement($title,$index,"image",["size"=>$size]);
    }

    public function number($title,$index,$format = NumberFormatter::DECIMAL){
        $this->getElement($title,$index,"number",["format"=>$format]);
    }

    private function getElement($title,$index,$type,$options) {
        $name = $type."_".$title;
        // Gérer l'update
        $this->manageUpdate($name,$type,$options);
        // Gérer l'affichage
        if (array_key_exists($index,$this->_data) && array_key_exists($name,$this->_data[$index]) && array_key_exists("data",$this->_data[$index][$name])) {
            echo $this->formatElement($this->_data[$index][$name]);
        } else {
            echo "update candide on the admin platform";
        }
    }

    protected function manageUpdate($name,$type,$options){
        throw new Exception('manageUpdate is not redefined in class which use IndexedElementsGetter');
    }

}