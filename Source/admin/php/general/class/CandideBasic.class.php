<?php

// Basic < CandideBasic

class CandideBasic extends Basic {

    use BackendPluginNotifier, JsonReader;

    protected $_page,
              $_data;

    public function __construct(String $page, Array $extensions = []) {
        $this->_page = $page;
        $this->_data = $this->readJsonFile($this->getPageUrl());
        parent::__construct($extensions);
    }

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