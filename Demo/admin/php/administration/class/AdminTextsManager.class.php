<?php
/**
 * AdminTextsManager.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Manager for text traductions
 * 
 * @since 1.0
 * No childclasses
 * 
 */
class AdminTextsManager {

    use JsonReader {
        JsonReader::readJsonFile as atm_readJsonFile;
    }

    private $_languagesPath = ROOT_DIR."/admin/config/languages/", $_texts = [];

    /**
     * AdminTextsManager constructor which set _texts according to language choosed in Config.json
     *
     * @param String $moduleName [Module name]
     */
    public function __construct(String $moduleName){
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
        $tmp_texts = $this->atm_readJsonFile($this->_languagesPath.$code.".json");
        $this->_texts = (key_exists($moduleName,$tmp_texts)) ? $tmp_texts[$moduleName] : [] ;
    }

    /**
     * Echo value of a specific key
     *
     * @param String $key [Key]
     * @return void
     */
    public function echo(String $key) {
        echo $this->get($key);
    }

    /**
     * Get value of a specific key
     *
     * @param String $key [Key]
     * @return String
     */
    public function get(String $key):String{
        if (key_exists($key,$this->_texts)) {
            return $this->_texts[$key];
        } else {
            return $key;
        }
    }

}