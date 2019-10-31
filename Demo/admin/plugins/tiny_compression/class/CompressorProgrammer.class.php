<?php

class CompressorProgrammer {

    use JsonReader;

    private $_waiting_compressions_url = ROOT_DIR."/admin/plugins/tiny_compression/tmp/waiting_compressions.json",
            $_is_running_url = ROOT_DIR."/admin/plugins/tiny_compression/tmp/is_running.txt";

    public function addToQueue(String $img_url) {
        $directoryUrl = dirname($this->_waiting_compressions_url);
        if (!is_dir($directoryUrl)){
            mkdir($directoryUrl,0777,true);
        }
        $json = $this->readJsonFile($this->_waiting_compressions_url);
        $json[] = $img_url;
        error_log("Add to tinify compression queue : ".$img_url);
        file_put_contents($this->_waiting_compressions_url,json_encode($json));
    }

    public function getNextFileToCompress():?String {
        $files = $this->readJsonFile($this->_waiting_compressions_url);
        if (count($files) > 0) {
            return array_values($files)[0];
        } else {
            return null;
        }
    }

    public function removeFromQueue(String $img_url) {
        $json = $this->readJsonFile($this->_waiting_compressions_url);
        foreach ($json as $key => $url) {
            if ($url == $img_url) {
                error_log("Remove from tinify compression queue : ".$img_url);
                unset($json[$key]);
                break;
            }
        }
        file_put_contents($this->_waiting_compressions_url,json_encode($json));
    }

    public function run(){
        $this->setIsRunning(true);
        register_shutdown_function(function(){
            $this->setIsRunning(false);
        });
    }

    private function setIsRunning(Bool $isRunning) {
        error_log($isRunning ? "--> Tinify compression start" : "--> Tinify compression ended");
        file_put_contents($this->_is_running_url,strval($isRunning));
    }

    public function isRunning():Bool {
        if (!file_exists($this->_is_running_url)) {
            return false;
        }
        return boolval(file_get_contents($this->_is_running_url));
    }

}