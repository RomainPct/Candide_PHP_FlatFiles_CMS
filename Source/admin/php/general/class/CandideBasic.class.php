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

    // Page name
    public function getPageName() {
        echo $this->formatTitle($this->_page);
    }

    protected function getPageUrl(){
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/base.json");
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