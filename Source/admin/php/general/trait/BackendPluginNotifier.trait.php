<?php

trait BackendPluginNotifier {

    use JsonReader;
    protected $_backendPlugins = null;

    protected function setBackendPlugins(){
        $this->_backendPlugins = [];
        foreach (glob(self::PLUGINS_DIRECTORY."*", GLOB_ONLYDIR) as $pluginFolder) {
            $plugin = $this->readJsonFile($pluginFolder."/config.json");
            if ($plugin["is_backend_extension"]) {
                $this->_backendPlugins[] = $pluginFolder."/eventHandler.php";
            }
        }
    }

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