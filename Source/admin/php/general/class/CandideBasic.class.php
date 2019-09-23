<?php

class CandideBasic extends Basic {

    use BackendPluginNotifier, JsonReader;

    protected $_page;
    protected $_data;
    protected $_updateCall = false;
    protected $_methods = [];

    public function __construct(String $page) {
        if (array_key_exists("updateAdminPlatform",$_GET)) {
            $this->_updateCall = $_GET["updateAdminPlatform"];
        }
        $this->_page = $page;
        $this->_data = $this->readJsonFile($this->getPageUrl());
    }

    // Gestion ajout de méthode via les plugins
    // function addMethod($name, $method) {
    //     $this->_methods[$name] = $method;
    // }

    // public function __call($name, $arguments) {
    //     return call_user_func_array( $this->_methods[$name], $arguments);
    // }

    // Elements
    // public function text($title,$wysiwyg = false){
    //     $this->getElement($title, null,"text",["wysiwyg"=>$wysiwyg]);
    // }

    // public function image($title,$size){
    //     $this->getElement($title, null,"image",["size"=>$size]);
    // }

    // public function number($title,$format = NumberFormatter::DECIMAL){
    //     $this->getElement($title, null,"number",["format"=>$format]);
    // }

    // protected function getElement($title,$index,$type,$options) {
    //     $name = $type."_".$title;
    //     // Gérer l'update
    //     $this->manageStructureUpdate($name,$type,$options);
    //     $this->manageDataUpdate($name,$type,$options);
    //     // Gérer l'affichage
    //     if (array_key_exists($index,$this->_data) && array_key_exists($name,$this->_data[$index]) && array_key_exists("data",$this->_data[$index][$name])) {
    //         echo $this->formatElement($this->_data[$index][$name]);
    //     } else {
    //         echo "update candide on the admin platform";
    //     }
    // }

    // Page name
    public function getPageName() {
        echo $this->formatTitle($this->_page);
    }

    protected function getPageUrl(){
        if (!file_exists(self::DATA_DIRECTORY.$this->_page)){
            mkdir(self::DATA_DIRECTORY.$this->_page,0777,true);
        }
        return self::DATA_DIRECTORY.$this->_page."/base.json";
    }

    protected function saveData(){
        if ($this->_updateCall) {
            file_put_contents($this->getPageUrl(),json_encode($this->_data));
            $this->sendNotification(Notification::CONTENT_SAVED,[
                "folder" => $this->getPageUrl()
            ]);
        }
    }

    /**
     * @return String
     */
    public function getPage(): String {
        return $this->_page;
    }

    public function save(){}

}