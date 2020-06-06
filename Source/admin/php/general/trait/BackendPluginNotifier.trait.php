<?php
/**
 * BackendPluginNotifier.trait.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Backend plugins manager which allow to send them update notifications
 * 
 * @since 1.0
 * 
 */
trait BackendPluginNotifier {

    use JsonReader {
        JsonReader::readJsonFile as bpn_readJsonFile;
    }

    protected $_backendPlugins = null;

    /**
     * Load backend plugins in $this->_backendPlugins
     *
     * @return void
     */
    private function setBackendPlugins(){
        $this->_backendPlugins = [];
        foreach (glob(self::PLUGINS_DIRECTORY."*", GLOB_ONLYDIR) as $pluginFolder) {
            $plugin = $this->bpn_readJsonFile($pluginFolder."/config.json");
            if ($plugin["is_backend_extension"]) {
                $this->_backendPlugins[] = $pluginFolder."/eventHandler.php";
            }
        }
    }

    /**
     * Send a notification to each backend plugins
     * Automatically set backend plugins if was null
     *
     * @param String $notif [Notification name avalaible as const of Notification class]
     * @param Array $infos [Data to be transfered to backend plugins]
     * @return void
     */
    protected function sendNotification(String $notif, Array $infos = []) {
        if ($this->_backendPlugins === null) {
            $this->setBackendPlugins();
        }
        $_GET["event"] = $notif;
        $_GET["informations"] = $infos;
        foreach ($this->_backendPlugins as $eventHandler) {
            require($eventHandler);
        }
    }

}