<?php
/**
 * PluginHelper.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Helper class for plugin developers (Read their config and launch async script)
 * 
 * @since 1.0
 * No childclasses
 * 
 */
class PluginHelper {

    use JsonReader;

    private $_config, $_pluginName;

    /**
     * Plugin Helper constructor 
     *
     * @param String $pluginName [Plugin name]
     */
    public function __construct(String $pluginName) {
        $this->_pluginName = $pluginName;
        $this->_config = $this->readJsonFile(ROOT_DIR."/admin/plugins/".$pluginName."/config.json");
    }

    /**
     * Read plugin config.json properties by key
     *
     * @param String $key [JSON key you want the value]
     * @return Mixed
     */
    public function getInfoInConfig(String $key) {
        if (key_exists($key,$this->_config)) {
            return $this->_config[$key];
        } else {
            trigger_error('The key "'.$key.'" does not exist in your plugin config',E_USER_WARNING);
        }
    }

    /**
     * Perform an async script
     *
     * @param String $scriptUrl [Script url from current plugin folder]
     * @param Array $data [Data to post to the script. Get it in the script via $_POST]
     * @return void
     */
    public function async(String $scriptUrl, Array $data = []) {
        $url = $_SERVER["HTTP_HOST"]."/admin/plugins/".$this->_pluginName."/".$scriptUrl;
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