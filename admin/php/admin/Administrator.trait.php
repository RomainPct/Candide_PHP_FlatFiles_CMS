<?php

trait Administrator {
    protected function getField($name,$type,$data,$fieldInfos) {
        $html = "<div class='inputContainer'><h2>".$this->formatTitle($name,true)."</h2>";
        switch ($type) {
            case "text":
                if (key_exists("wysiwyg",$fieldInfos) && $fieldInfos["wysiwyg"]) {
                    $data = $this->delete_all_between('data-trix-attachment="{','}" data-trix-content-type',$data);
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

    private function delete_all_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return $this->delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
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