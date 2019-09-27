<?php

class PluginHelper {

    use JsonReader;

    private $_config, $_pluginName;

    public function __construct(String $pluginName) {
        $this->_pluginName = $pluginName;
        $this->_config = $this->readJsonFile(ROOT_DIR."/admin/plugins/".$pluginName."/config.json");
    }

    public function getInfoInConfig(String $key) {
        if (key_exists($key,$this->_config)) {
            return $this->_config[$key];
        } else {
            trigger_error('The key "'.$key.'" does not exist in your plugin config',E_USER_WARNING);
        }
    }

    public function async(String $urlFromPluginFolder, Array $data = []) {
        $url = $_SERVER["HTTP_ORIGIN"]."/admin/plugins/".$this->_pluginName."/".$urlFromPluginFolder;
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER =>true,
            CURLOPT_NOSIGNAL => 1, //to timeout immediately if the value is < 1000 ms
            CURLOPT_TIMEOUT_MS => 50, //The maximum number of mseconds to allow cURL functions to execute
            CURLOPT_VERBOSE => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

}