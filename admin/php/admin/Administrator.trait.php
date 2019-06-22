<?php

trait Administrator {
    protected function getField($name,$type,$data,$fieldInfos) {
        $html = "<div class='inputContainer'><h2>".$this->formatTitle($name,true)."</h2>";
        switch ($type) {
            case "text":
                if (key_exists("wysiwyg",$fieldInfos) && $fieldInfos["wysiwyg"]) {
                    $data = htmlspecialchars($data,ENT_QUOTES);
                    $html = $html."<input type='hidden' id='".$name."' name='".$name."' value='".$data."'><trix-editor input='".$name."'></trix-editor>";
                } else {
                    $html = $html."<textarea name='".$name."'>".$data."</textarea>";
                }
                break;
            case "image":
                $style = "style='width: ".$fieldInfos["width"]."px; height: ".$fieldInfos["height"]."px'";
                $html = $html."<div ".$style." class='image_input_preview'><img id='image_".$name."' class='fullHeight' src='".$data."'/></div><label for='".$name."'>Modifier</label><input id='".$name."' type='file' name='".$name."'/>";
                break;
        }
        $html = $html."</div>";
        return $html;
    }

    protected function deleteFiles($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            foreach( $files as $file ){
                $this->deleteFiles( $file );
            }
            rmdir($target);
        } elseif(is_file($target)) {
            unlink( $target );
        }
    }

    protected function cleanFileName($key,$name):String {
        $fileName = preg_replace("/[^a-zA-Z0-^._]/", "_", $name);
        return strtolower($key."_".time().$fileName);
    }

    protected function savePicture($key,$file,$directory,$entry,$struct = []):String {
        if (!file_exists(self::FILES_DIRECTORY.$directory)) {
            mkdir(self::FILES_DIRECTORY.$directory,0777,true);
        }
        $fileName = $this->cleanFileName($key,$file["name"]);
        // Resize de l'image
        if (key_exists("width",$entry)){
            $img = $this->resize($file["tmp_name"],$entry["width"],$entry["height"]);
        } else {
            $img = $this->resize($file["tmp_name"],$struct["width"],$struct["height"]);
        }
        // Enregistrer l'image dans un dossier
        if ($img[1] == "png") {
            imagepng($img[0], self::FILES_DIRECTORY.$directory."/".$fileName);
        } else {
            imagejpeg($img[0], self::FILES_DIRECTORY.$directory."/".$fileName, 100);
        }
        $this->deleteFiles("../..".$entry["data"]);
        return "/CandideData/files/".$directory."/".$fileName;
    }

}