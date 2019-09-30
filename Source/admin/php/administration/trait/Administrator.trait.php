<?php

trait Administrator {

    use BackendPluginNotifier, FilesManager;

    protected $_texts = null, $_extensionsUsed = [];

    /**
     * Set class type to Basic::TYPE_ADMINISTRATOR and not load any plugins
     *
     * @param String[] $extensions
     * @return void
     */
    protected function loadPlugins(Array $extensions = []){
        $this->_type = Basic::TYPE_ADMINISTRATOR;
        $_GET["extension_type"] = $this->_type;
    }

    /**
     * Return html inputContainer for a specific field
     *
     * @param String $name [Field name]
     * @param String $data [Field data]
     * @param Array $fieldInfos [All field informations]
     * @return void
     */
    protected function getField(String $name, String $data,Array $fieldInfos) {
        if ($this->_texts == null){
            $this->_texts = new AdminTextsManager("administrator");
        }
        return "<div class='inputContainer'><h2>".$this->formatTitle($name,true)."</h2>".$this->getFieldContent($name, $data, $fieldInfos)."</div>";
    }

    /**
     * Return field content for a specifif field
     *
     * @param String $name [Field name]
     * @param String $data [Field data]
     * @param Array $fieldInfos [All field informations]
     * @return String [HTML field content]
     */
    private function getFieldContent(String $name, String $data, Array $fieldInfos):String{
        switch ($fieldInfos["type"]) {
            case "text":
                return $this->getTextInput($name, $data, $fieldInfos);
            case "image":
                return $this->getImageInput($name, $data, $fieldInfos);
            case "number":
                return $this->getNumberInput($name, $data);
            default:
                return $this->getCustomField($name, $data, $fieldInfos);
        }
    }

    /**
     * Generate HTML of text input
     *
     * @param String $name [Field name]
     * @param String $data [Field data]
     * @param Array $fieldInfos [All field informations]
     * @return String [HTML text input]
     */
    private function getTextInput(String $name, String $data, Array $fieldInfos):String {
        if (key_exists("wysiwyg",$fieldInfos) && $fieldInfos["wysiwyg"]) {
            $data = htmlspecialchars($data,ENT_QUOTES);
            return "<div class='pell-input-box'><input type='hidden' class='wysiwyg-output' name='".$name."' value='".$data."'><div class='pell'></div><input class='pell-file-input' type='file' accept='image/*'></input></div>";
        } else {
            return "<textarea name='".$name."'>".$data."</textarea>";
        }
    }

    /**
     * Generate HTML of image input
     *
     * @param String $name [Field name]
     * @param String $data [Field data]
     * @param Array $fieldInfos [All field informations]
     * @return String [HTML image input]
     */
    private function getImageInput(String $name, String $data, Array $fieldInfos):String {
        $style = "style='width: ".$fieldInfos["width"]."px; height: ".$fieldInfos["height"]."px'";
        return "<div ".$style." class='image_input_preview'><label for='".$name."'>".$this->_texts->get("edit")."</label><img id='image_".$name."' data-cropping-enable='".strval($fieldInfos["crop"])."' class='fullHeight' src='".$data."'/><input id='".$name."' type='file' accept='image/*' name='".$name."' class='classic-image-input'/></div>";
    }

    /**
     * Generate HTML of number input
     *
     * @param String $name [Field name]
     * @param String $data [Field data]
     * @param Array $fieldInfos [All field informations]
     * @return String [HTML number input]
     */
    private function getNumberInput(String $name, String $data):String {
        return "<input placeholder='ex: 12.67' name='".$name."' type='text' value='".$data."'>";
    }

    /**
     * Generate HTML of a custom field input if exists else trig an error
     *
     * @param String $name [Field name]
     * @param String $data [Field data]
     * @param Array $fieldInfos [All field informations]
     * @return String [HTML custom input]
     */
    private function getCustomField(String $name, String $data, Array $fieldInfos):String {
        $customFunctionName = "get".$fieldInfos["type"]."Input";
        $extension_path = $fieldInfos["plugin"]."/Candide.extension.php";
        $fromroot_path = ROOT_DIR."/admin/plugins/".$extension_path;
        if (!key_exists($customFunctionName,$this->_methods)) {
            if (file_exists($fromroot_path)) {
                include $fromroot_path;   
                if (!key_exists($customFunctionName,$this->_methods)) {
                    trigger_error($customFunctionName." function is not defined by ".$extension_path,E_USER_ERROR);
                }
            } else {
                trigger_error($extension_path." is not found in plugins folder",E_USER_ERROR);
            }
        }
        $this->_extensionsUsed[$fieldInfos["plugin"]] = $fieldInfos["type"];
        return $this->_methods[$customFunctionName]($name, $data, $fieldInfos);
    }

    /**
     * Insert custom css and js if some extensions need it
     *
     * @return void
     */
    public function getCustomCSSAndJS(){
        $custom = "";
        foreach ($this->_extensionsUsed as $plugin => $type) {
            $custom = $custom.$this->getCustomCSS($plugin,$type).$this->getCustomJS($plugin,$type);
        }
        echo $custom;
    }

    /**
     * Link CSS for a specific input if needed
     *
     * @param String $plugin [Plugin name]
     * @param String $type [Field type]
     * @return String [HTML CSS link]
     */
    private function getCustomCSS(String $plugin, String $type):String {
        $css_path = "plugins/".$plugin."/admin/".$type.".css";
        if (file_exists($css_path)) {
            return '<link rel="stylesheet" href="'.$css_path.'">';
        } else {
            return "";
        }
    }

    /**
     * Insert Javascript for a specific input if needed
     *
     * @param String $plugin [Plugin name]
     * @param String $type [Field type]
     * @return String [HTML Javascript src]
     */
    private function getCustomJS(String $plugin, String $type):String {
        $js_path = "plugins/".$plugin."/admin/".$type.".js";
        if (file_exists($js_path)) {
            return '<script src="'.$js_path.'"></script>';
        } else {
            return "";
        }
    }

}