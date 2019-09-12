<?php

class AdminTextsManager {

    private $_languagesPath = ROOT_DIR."/admin/config/languages/",
            $_texts = [];

    public function __construct($page){
        // echo $page."<br>";
        $code = substr(LOCALE,0,2);
        if (!file_exists($this->_languagesPath.$code.".json")) {
            $code = "en";
        }
        $this->_texts = json_decode(file_get_contents($this->_languagesPath.$code.".json"),true)[$page];
        // var_dump($this->_texts);
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