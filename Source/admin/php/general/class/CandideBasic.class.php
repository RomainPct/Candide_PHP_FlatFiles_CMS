<?php

// Basic < CandideBasic

class CandideBasic extends Basic {

    use BackendPluginNotifier, JsonReader;

    protected $_page, $_data;

    /**
     * CandideBasic constructor which set _page, _data & _extensions
     *
     * @param String $page [Candide instance name]
     * @param Array $extensions [Extensions needed for this instance]
     */
    public function __construct(String $page, Array $extensions = []) {
        $this->_page = $page;
        $this->_data = $this->readJsonFile($this->getPageUrl());
        parent::__construct($extensions);
    }

    /**
     * Echo instance name formatted as title
     *
     * @return void
     */
    public function getPageName() {
        echo $this->formatTitle($this->_page);
    }

    /**
     * Echo instance base.json path from root
     *
     * @return String
     */
    protected function getPageUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_page."/base.json");
    }

    /**
     * Save data if called from updateAdminPlatform.php
     *
     * @return void
     */
    protected function saveData(){
        if ($this->_updateCall) {
            file_put_contents($this->getPageUrl(),json_encode($this->_data));
            $this->sendNotification(Notification::CONTENT_SAVED,[
                "folder" => $this->getPageUrl()
            ]);
        }
    }

    /**
     * Return page name
     *
     * @return String [Current Candide Instance name]
     */
    public function getPage(): String {
        return $this->_page;
    }

    /**
     * Placeholder save function
     *
     * @return void
     */
    public function save(){
        trigger_error("Save function is not implemented",E_USER_ERROR);
    }

}