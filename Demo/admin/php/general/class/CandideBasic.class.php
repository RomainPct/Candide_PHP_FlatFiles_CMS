<?php
/**
 * CandideBasic.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Base for each candide class which need to access a CandideData file
 * 
 * @since 1.0
 * Basic < CandideBasic
 * 
 */
class CandideBasic extends Basic {

    use BackendPluginNotifier, JsonReader;

    protected $_instanceName, $_data;

    /**
     * CandideBasic constructor which set _instanceName, _data & _extensions
     *
     * @param String $instanceName [Candide instance name]
     * @param Array $extensions [Extensions needed for this instance]
     */
    public function __construct(String $instanceName, Array $extensions = []) {
        $this->_instanceName = $instanceName;
        $this->_data = $this->readJsonFile($this->getInstanceUrl());
        parent::__construct($extensions);
    }

    /**
     * Echo instance name formatted as title
     *
     * @return void
     */
    public function echoFormattedInstanceName() {
        echo $this->formatTitle($this->_instanceName);
    }

    /**
     * Echo instance base.json path from root
     *
     * @return String
     */
    protected function getInstanceUrl():String{
        return $this->getFileUrl(self::DATA_DIRECTORY.$this->_instanceName."/base.json");
    }

    /**
     * Save data if called from updateAdminPlatform.php
     *
     * @return void
     */
    protected function saveData(){
        if ($this->_updateCall) {
            file_put_contents($this->getInstanceUrl(),json_encode($this->_data));
            $this->sendNotification(Notification::CONTENT_SAVED,[
                "folder" => $this->getInstanceUrl()
            ]);
        }
    }

    /**
     * Return Candide instance name
     *
     * @return String [Current Candide instance name]
     */
    public function getInstanceName(): String {
        return $this->_instanceName;
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