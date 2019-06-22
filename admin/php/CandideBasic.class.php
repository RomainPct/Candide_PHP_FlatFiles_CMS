<?php

class CandideBasic extends Basic {

    protected $_page;
    protected $_data;
    protected $_updateCall = false;

    public function getPageName() {
        echo $this->formatTitle($this->_page);
    }

    protected function getPageUrl(){
        if (!file_exists(self::DATA_DIRECTORY.$this->_page)){
            mkdir(self::DATA_DIRECTORY.$this->_page,0777,true);
        }
        return self::DATA_DIRECTORY.$this->_page."/base.json";
    }

    public function __construct(String $page) {
        if (array_key_exists("updateAdminPlatform",$_GET)) {
            $this->_updateCall = $_GET["updateAdminPlatform"];
        }
        $this->_page = $page;
        if (!file_exists($this->getPageUrl())){
            $this->_data = [];
        } else {
            $this->_data = json_decode(file_get_contents($this->getPageUrl()),true);
        }
    }

    protected function saveData(){
        if ($this->_updateCall) {
            file_put_contents($this->getPageUrl(),json_encode($this->_data));
        }
    }

    protected function resize($tmp,$width,$height) {
        $type = "jpg";
        $imgSize = getimagesize($tmp);
        $img = imagecreatefromjpeg($tmp);
        if ($img == false){
            $type = "png";
            $img = imagecreatefrompng($tmp);
            imagealphablending($img,true);
            imagesavealpha($img,true);
        }
        $newImg = imagecreatetruecolor($width , $height) or die ("Erreur");
        imagealphablending($newImg,true);
        $transparent = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
        imagefill($newImg, 0, 0, $transparent);
        if ( $height/$width > $imgSize[1]/$imgSize[0] ) {
            $captureHeight = $imgSize[1];
            $captureWidth = $imgSize[1] * ($width/$height);
            $offsetX = ($imgSize[0] - $captureWidth) / 2;
            $offsetY = 0;
        } else {
            $captureWidth = $imgSize[0]; // t'es un tocard
            $captureHeight = $imgSize[0] * ($height/$width);
            $offsetX = 0;
            $offsetY = ($imgSize[1] - $captureHeight) / 2;
        }
        imagecopyresampled($newImg, $img, 0,0, $offsetX,$offsetY, $width, $height, $captureWidth,$captureHeight);
        imagealphablending($img, false);
        imagesavealpha($newImg,true);
        return [$newImg,$type];
    }

    /**
     * @return String
     */
    public function getPage(): String {
        return $this->_page;
    }

    public function end(){}

}