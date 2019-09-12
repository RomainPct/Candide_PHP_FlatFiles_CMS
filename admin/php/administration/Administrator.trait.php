<?php

trait Administrator {

    protected function getField($name,$type,$data,$fieldInfos) {
        $html = "<div class='inputContainer'><h2>".$this->formatTitle($name,true)."</h2>";
        switch ($type) {
            case "text":
                if (key_exists("wysiwyg",$fieldInfos) && $fieldInfos["wysiwyg"]) {
                    $data = htmlspecialchars($data,ENT_QUOTES);
                    $html = $html."<div class='pell-input-box'><input type='hidden' class='wysiwyg-output' name='".$name."' value='".$data."'><div class='pell'></div><input class='pell-file-input' type='file' accept='image/*'></input></div>";
                    // $html = $html."<input type='hidden' id='".$name."' name='".$name."' value='".$data."'><trix-editor input='".$name."'></trix-editor>";
                } else {
                    $html = $html."<textarea name='".$name."'>".$data."</textarea>";
                }
                break;
            case "image":
                $style = "style='width: ".$fieldInfos["width"]."px; height: ".$fieldInfos["height"]."px'";
                $html = $html."<div ".$style." class='image_input_preview'><img id='image_".$name."' class='fullHeight' src='".$data."'/></div><label for='".$name."'>Modifier</label><input id='".$name."' type='file' accept='image/*' name='".$name."' class='classic-image-input'/>";
                break;
            case "number":
                $html = $html."<input placeholder='ex: 12.67' name='".$name."' type='text' value='".$data."'>";
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
        if (key_exists("data",$entry)) {
            $this->deleteFiles(ROOT_DIR.$entry["data"]);
        }
        return "/CandideData/files/".$directory."/".$fileName;
    }

    protected function removeWysiwygFiles($jsonWysiwygFilesToDelete,$collectionData = []) {
        $wysiwygFilesToDelete = json_decode($jsonWysiwygFilesToDelete);
        if (is_array($wysiwygFilesToDelete)) {
            // Filter files to delete which finally are still used
            $data = array_merge($this->_data,$collectionData);
            $wysiwygFilesToDelete = array_filter($wysiwygFilesToDelete,function($file) use ($data){
                $keep = true;
                foreach ($data as $fieldData) {
                    if (is_array($fieldData) && key_exists("wysiwyg",$fieldData) && $fieldData["wysiwyg"] && strstr($fieldData["data"],$file) != false) {
                        $keep = false;
                    }
                }
                return $keep;
            });
            foreach ($wysiwygFilesToDelete as $file){
                $this->deleteFiles(ROOT_DIR.$file);
            }
        }
    }

}