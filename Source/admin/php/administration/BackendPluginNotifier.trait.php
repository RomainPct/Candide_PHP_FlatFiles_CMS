<?php

trait BackendPluginNotifier {

    protected $_backendPlugins = null;

    protected function setBackendPlugins(){
        $this->_backendPlugins = [];
        foreach (glob(self::PLUGINS_DIRECTORY."*", GLOB_ONLYDIR) as $pluginFolder) {
            $plugin = json_decode(file_get_contents($pluginFolder."/config.json"),true);
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

class Notification {

    const NEW_PICTURE_SAVED = "newPictureSaved";
    const CONTENT_SAVED = "content_saved";

}