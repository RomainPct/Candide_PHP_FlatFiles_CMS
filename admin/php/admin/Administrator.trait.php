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
                $html = $html."<img id='image_".$name."' src='".$data."'/><label for='".$name."'>Modifier</label><input id='".$name."' type='file' name='".$name."'/>";
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
}