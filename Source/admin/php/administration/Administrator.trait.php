<?php

trait Administrator {

    protected $_texts = null;

    protected function getField($name,$type,$data,$fieldInfos) {
        if ($this->_texts == null){
            $this->_texts = new AdminTextsManager("administrator");
        }
        $html = "<div class='inputContainer'><h2>".$this->formatTitle($name,true)."</h2>";
        switch ($type) {
            case "text":
                if (key_exists("wysiwyg",$fieldInfos) && $fieldInfos["wysiwyg"]) {
                    $data = htmlspecialchars($data,ENT_QUOTES);
                    $html = $html."<div class='pell-input-box'><input type='hidden' class='wysiwyg-output' name='".$name."' value='".$data."'><div class='pell'></div><input class='pell-file-input' type='file' accept='image/*'></input></div>";
                } else {
                    $html = $html."<textarea name='".$name."'>".$data."</textarea>";
                }
                break;
            case "image":
                $style = "style='width: ".$fieldInfos["width"]."px; height: ".$fieldInfos["height"]."px'";
                $html = $html."<div ".$style." class='image_input_preview'><label for='".$name."'>".$this->_texts->get("edit")."</label><img id='image_".$name."' class='fullHeight' src='".$data."'/><input id='".$name."' type='file' accept='image/*' name='".$name."' class='classic-image-input'/></div>";
                break;
            case "number":
                $html = $html."<input placeholder='ex: 12.67' name='".$name."' type='text' value='".$data."'>";
        }
        $html = $html."</div>";
        return $html;
    }

    protected function deleteFiles($target) {
        echo "Delete this target : ".$target;
        // Only allow to delete files into CandideData
        if (strpos($target,"CandideData/") !== false) {
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

    protected function removeWysiwygFiles($url,$collectionData = []) {
        // Get all wysiwyg fields in one string
        $wysiwygData = implode(
                    array_column(
                        array_filter(
                            array_merge($this->_data,$collectionData),
                            function($d){
                                return $d["type"] == "text" && $d["wysiwyg"] === true;
                            }
                        )
                    ,"data")
                );
        // Get each image url from the wysiwyg fields
        preg_match_all("/<img src=\"([\s\S]+?)\">/", $wysiwygData, $matches,PREG_SET_ORDER);
        $usefullFiles = array_map(function($entry){
            return $entry[1];
        },$matches);
        // Get all images in the current wysiwyg folder
        $filesInFolder = glob( $url.'/wysiwyg/*', GLOB_MARK );
        // Remove useless files
        foreach ($filesInFolder as $fileInFolder) {
            $isUseless = true;
            foreach ($usefullFiles as $usefullFile) {
                if (strpos($fileInFolder,$usefullFile) !== false) {
                    $isUseless = false;
                    break;
                }
            }
            // If file is useless -> delete it
            if ($isUseless) {
                $this->deleteFiles($fileInFolder);
            }
        }
    }

}