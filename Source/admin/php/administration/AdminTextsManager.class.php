<?php

class AdminTextsManager {

    private $_languagesPath = ROOT_DIR."/admin/config/languages/",
            $_texts = [];

    public function __construct($page){
        $code = substr(LOCALE,0,2);
        if (!file_exists($this->_languagesPath.$code.".json")) {
            $code = "en";
        }
        $tmp_texts = json_decode(file_get_contents($this->_languagesPath.$code.".json"),true);
        $this->_texts = (key_exists($page,$tmp_texts)) ? $tmp_texts[$page] : [] ;
    }

    public function echo($key) {
        echo $this->get($key);
    }

    public function get($key) {
        if (key_exists($key,$this->_texts)) {
            return $this->_texts[$key];
        } else {
            return $key;
        }
    }

}