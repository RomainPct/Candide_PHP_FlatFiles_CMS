<?php

trait FilesManager {

    protected function savePicture($key,$file,$directory,$entry = [],$struct = []):String {
        $fileName = $this->cleanFileName($key,$file["name"]);
        $finalPath = $this->getFileUrl(self::FILES_DIRECTORY.$directory."/".$fileName);
        // Resize the picture or save it if resizing is unecessary
        if (key_exists("width",$entry) && key_exists("height",$entry)){
            $img = $this->resize($file["tmp_name"],$entry["width"],$entry["height"],$entry["crop"]);
        } else if (key_exists("width",$struct) && key_exists("height",$struct)) {
            $img = $this->resize($file["tmp_name"],$struct["width"],$struct["height"],$entry["crop"]);
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

    private function resize(String $tmp,$width,$height,Bool $crop) {
        $type = "jpg";
        $imgSize = getimagesize($tmp);
        $source = imagecreatefromjpeg($tmp);
        if ($source == false){
            $type = "png";
            $source = imagecreatefrompng($tmp);
        }
        $destinationRatio = $height/$width;
        $imageRatio = $imgSize[1]/$imgSize[0];
        if ( ($destinationRatio > $imageRatio && $crop) || ($destinationRatio <= $imageRatio && !$crop) ) {
            $captureHeight = $imgSize[1];
            $captureWidth = $imgSize[1] * (1/$destinationRatio);
            $offsetX = ($imgSize[0] - $captureWidth) / 2;
            $offsetY = 0;
        } else {
            $captureWidth = $imgSize[0]; // t'es un tocard
            $captureHeight = $imgSize[0] * $destinationRatio;
            $offsetX = 0;
            $offsetY = ($imgSize[1] - $captureHeight) / 2;
        }
        $newImg = imagecreatetruecolor($width , $height) or die ("Erreur");
        imagesavealpha($newImg,true);
        imagefill($newImg, 0, 0, imagecolorallocatealpha($newImg, 0, 0, 0, 127));
        imagecopyresampled($newImg, $source, 0,0, $offsetX, $offsetY, $width, $height, $captureWidth,$captureHeight);
        imagefill($newImg, 0, 0, imagecolorallocatealpha($newImg, 0, 0, 0, 127));
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