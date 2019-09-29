<?php

trait ElementsGetter {

    use DataFormatter;

    public function text(String $title, Bool $wysiwyg = false){
        $this->getElement($title,"text",["wysiwyg"=>$wysiwyg]);
    }

    public function image(String $title, Array $size, Bool $crop = true){
        $this->getElement($title,"image",[
            "width"=>$size[0],
            "height"=>$size[1],
            "crop" => $crop
            ]);
    }

    public function number(String $title, Int $format = NumberFormatter::DECIMAL){
        $this->getElement($title,"number",["format"=>$format]);
    }

    protected function getElement(String $title,String $type,Array $options) {
        $name = $type."_".$title;
        // Gérer l'update
        if ($this->_updateCall) {
            $this->manageUpdate($name,$type,$options);
        }
        // Gérer l'affichage
        if (array_key_exists($name,$this->_data) && array_key_exists("data",$this->_data[$name])) {
            echo $this->formatElement($this->_data[$name]);
        } else {
            echo "Update candide on the admin platform";
        }
    }

    protected function manageUpdate(String $name, String $type, Array $options){
        $this->__call("manageUpdate",[$name,$type,$options]);
    }

}