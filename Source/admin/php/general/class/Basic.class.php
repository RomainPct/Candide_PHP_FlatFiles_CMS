<?php

// Level 0

class Basic {

    const DATA_DIRECTORY = ROOT_DIR."/CandideData/content/";
    const FILES_DIRECTORY = ROOT_DIR."/CandideData/files/";
    const PLUGINS_DIRECTORY = ROOT_DIR."/admin/plugins/";

    const TYPE_PAGE = "page";
    const TYPE_COLLECTION = "collection";
    const TYPE_COLLECTION_ITEM = "collection_item";
    const TYPE_ADMINISTRATOR = "administrator";

    protected $_type = null,
              $_methods = [];

    public function __construct(Array $extensions = []) {
        $this->loadPlugins($extensions);
    }

    protected function loadPlugins(Array $extensions){
        if ($this->_type != null) {
            $_GET["extension_type"] = $this->_type;
            foreach ($extensions as $extension) {
                include ROOT_DIR."/admin/plugins/".$extension."/Candide.extension.php";
            }   
        }
    }

    // Gestion ajout de méthode via les plugins
    function addMethod($name, $method) {
        if ($this->_type != null) {
            $this->_methods[$name] = $method;
        }
    }
    
    public function __call($name, $arguments) {
        if (key_exists($name,$this->_methods)) {
            return call_user_func_array( $this->_methods[$name], $arguments);   
        } else {
            trigger_error('Call to undefined method  "'.$name.'()"');
        }
    }

    protected function formatTitle(String $title, Bool $removingFirstPart = false):String {
        if ($removingFirstPart) {
            $title = ltrim(strstr($title,"_"),"_");
        }
        return ucfirst(str_replace("_"," ",$title));
    }

    protected function getFileUrl(String $url) : String {
        $directoryUrl = dirname($url);
        if (!is_dir($directoryUrl)){
            mkdir($directoryUrl,0777,true);
        }
        return $url;
    }

}