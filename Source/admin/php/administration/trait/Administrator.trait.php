<?php

trait Administrator {

    use BackendPluginNotifier, FilesManager;

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
                $html = $html."<div ".$style." class='image_input_preview'><label for='".$name."'>".$this->_texts->get("edit")."</label><img id='image_".$name."' data-cropping-enable='".strval($fieldInfos["crop"])."' class='fullHeight' src='".$data."'/><input id='".$name."' type='file' accept='image/*' name='".$name."' class='classic-image-input'/></div>";
                break;
            case "number":
                $html = $html."<input placeholder='ex: 12.67' name='".$name."' type='text' value='".$data."'>";
        }
        $html = $html."</div>";
        return $html;
    }

}