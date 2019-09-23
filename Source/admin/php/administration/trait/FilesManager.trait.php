<?php

trait FilesManager {

    protected function savePicture($key,$file,$directory,$entry = [],$struct = []):String {
        $fileName = $this->cleanFileName($key,$file["name"]);
        $finalPath = $this->getFileUrl(self::FILES_DIRECTORY.$directory."/".$fileName);
        // Resize the picture or save it if resizing is unecessary
        if (key_exists("width",$entry) && key_exists("height",$entry)){
            $img = $this->resize($file["tmp_name"],$entry["width"],$entry["height"]);
        } else if (key_exists("width",$struct) && key_exists("height",$struct)) {
            $img = $this->resize($file["tmp_name"],$struct["width"],$struct["height"]);
        } else {
            move_uploaded_file($file["tmp_name"],$finalPath);
        }
        // Save img if she was resized
        if (isset($img)) {
            if ($img[1] == "png") {
                imagepng($img[0], $finalPath);
            } else {
                imagejpeg($img[0], $finalPath, 100);
            }   
        }
        // Remove the old file if it exists
        if (key_exists("data",$entry)) {
            $this->deleteFiles(ROOT_DIR.$entry["data"]);
        }
        $url = "/CandideData/files/".$directory."/".$fileName;
        $this->sendNotification(Notification::NEW_PICTURE_SAVED, [
            "url" => ROOT_DIR.$url
        ]);
        return $url;
    }

    private function resize($tmp,$width,$height) {
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

    private function cleanFileName($key,$name):String {
        $fileName = preg_replace("/[^a-zA-Z0-^._]/", "_", $name);
        return strtolower($key."_".time().$fileName);
    }

    protected function deleteFiles($target) {
        echo "Delete this target : ".$target."\n";
        // Only allow to delete files into CandideData
        if (strpos($target,"CandideData/") !== false) {
            if(is_dir($target)){
                $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
                foreach( $files as $file ){
                    $this->deleteFiles( $file );
                }
                rmdir($target);
            } elseif(is_file($target)) {
                unlink( $target );
            }
        }
    }

}