<?php
/**
 * Basic.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Base for each Candide class
 * 
 * @since 1.0
 * No childclasses
 * 
 */
class Basic {

    const DATA_DIRECTORY = ROOT_DIR."/CandideData/content/";
    const FILES_DIRECTORY = ROOT_DIR."/CandideData/files/";
    const PLUGINS_DIRECTORY = ROOT_DIR."/admin/plugins/";

    const TYPE_ITEM = "item";
    const TYPE_FIELDS_GENERATOR = "fields_generator";

    protected $_type = null, $_methods = [], $_updateCall = false;


    /**
     * Basic constructor which set $_updateCall & load needed extensions methods
     *
     * @param String[] $extensions [Extensions needed for this instance]
     */
    public function __construct(Array $extensions = []) {
        if (array_key_exists("updateAdminPlatform",$_GET)) {
            $this->_updateCall = $_GET["updateAdminPlatform"];
        }
        $this->loadPlugins($extensions);
    }

    /**
     * Load extension plugins
     *
     * @param String[] $extensions [Extensions to load]
     * @return void
     */
    protected function loadPlugins(Array $extensions){
        if ($this->_type != null) {
            $_GET["extension_type"] = $this->_type;
            foreach ($extensions as $extension) {
                include ROOT_DIR."/admin/plugins/".$extension."/Candide.extension.php";
            }   
        }
    }

    /**
     * Add a method to class where ($_type == $_GET["extension_type"])
     *
     * @param String $name [Callable name]
     * @param Callable $callable [Callable action]
     * @return void
     */
    function addCallable(String $name, Callable $callable) {
        if ($this->_type != null) {
            $this->_methods[$name] = $callable;
        } else {
            trigger_error("This class can't received new methods",E_USER_ERROR);
        }
    }
    
    /**
     * Function called when an unknown function is called
     * Call an extension method if exists or trig an error
     */
    public function __call($name, $arguments) {
        if (key_exists($name,$this->_methods)) {
            return call_user_func_array( $this->_methods[$name], $arguments);   
        } else {
            trigger_error('Call to undefined method  "'.$name.'()"');
        }
    }

    /**
     * Format a text as readable title
     *
     * @param String $title [Nonformatted title]
     * @param Bool $removingFirstPart [Remove everyting before first _ or not]
     * @return String [Formatted title]
     */
    protected function formatTitle(String $title, Bool $removingFirstPart = false):String {
        if ($removingFirstPart) {
            $title = ltrim(strstr($title,"_"),"_");
        }
        return ucfirst(str_replace("_"," ",$title));
    }

    /**
     * Create directory if needed and return $url
     *
     * @param String $url [Url you want to check]
     * @return String [Clean url]
     */
    protected function getFileUrl(String $url) : String {
        $directoryUrl = dirname($url);
        if (!is_dir($directoryUrl)){
            mkdir($directoryUrl,0777,true);
        }
        return $url;
    }

}