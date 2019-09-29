<?php

// trait IndexedElementsGetter {

//     use DataFormatter;

//     public function text(String $title, Int $index, Bool $wysiwyg = false){
//         $this->getElement($title,$index,"text",["wysiwyg"=>$wysiwyg]);
//     }

//     public function image(String $title, Int $index, Array $size, Bool $crop = true){
//         $this->getElement($title,$index,"image",[
//             "width"=>$size[0],
//             "height"=>$size[1],
//             "crop" => $crop
//             ]);
//     }

//     public function number(String $title, Int $index, Int $format = NumberFormatter::DECIMAL){
//         $this->getElement($title,$index,"number",["format"=>$format]);
//     }

//     protected function getElement(String $title,Int $index,String $type, Array $options) {
//         $name = $type."_".$title;
//         // Gérer l'update
//         if ($this->_updateCall) {
//             $this->manageUpdate($name,$type,$options);
//         }
//         // Gérer l'affichage
//         if (array_key_exists($index,$this->_data) && array_key_exists($name,$this->_data[$index]) && array_key_exists("data",$this->_data[$index][$name])) {
//             echo $this->formatElement($this->_data[$index][$name]);
//         } else {
//             echo "update candide on the admin platform";
//         }
//     }

//     protected function manageUpdate(String $name, String $type, Array $options){
//         throw new Exception('manageUpdate is not redefined in class which use IndexedElementsGetter');
//     }

// }