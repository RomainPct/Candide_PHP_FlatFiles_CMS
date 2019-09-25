<?php

// Level 0

class AdminTextsManager {

    use JsonReader;

    private $_languagesPath = ROOT_DIR."/admin/config/languages/",
            $_texts = [];

    public function __construct($page){
        $code = substr(LOCALE,0,2);
        if (!file_exists($this->_languagesPath.$code.".json")) {
            $fileURL = "https://raw.githubusercontent.com/RomainPct/Candide_PHP_FlatFiles_CMS/master/AdminTraductions/".$code.".json";
            $tradsData = @fopen($fileURL,"r");
            if ($tradsData == false) {
                $code = "en";
            } else {
                file_put_contents($this->_languagesPath.$code.".json",$tradsData);
            }
        }
        $tmp_texts = $this->readJsonFile($this->_languagesPath.$code.".json");
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