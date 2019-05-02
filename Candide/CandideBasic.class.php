<?php

class CandideBasic extends Basic {

    protected $_page;
    protected $_data;
    protected $_updateCall = false;

    protected function getPageUrl(){
        if (!file_exists(self::DATA_DIRECTORY.$this->_page)){
            mkdir(self::DATA_DIRECTORY.$this->_page,0777,true);
        }
        return self::DATA_DIRECTORY.$this->_page."/base.json";
    }

    public function __construct(String $page) {
        if (array_key_exists("updateAdminPlatform",$_GET)) {
            $this->_updateCall = $_GET["updateAdminPlatform"];
        }
        $this->_page = $page;
        if (!file_exists($this->getPageUrl())){
            $this->_data = [];
        } else {
            $this->_data = json_decode(file_get_contents($this->getPageUrl()),true);
        }
    }

    protected function saveData(){
        if ($this->_updateCall) {
            file_put_contents($this->getPageUrl(),json_encode($this->_data));
        }
    }

    /**
     * @return String
     */
    public function getPage(): String {
        return $this->_page;
    }

    public function end(){}

}