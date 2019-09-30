<?php

trait FilesManager {

    /**
     * Resize and save a picture while cleaning old image 
     *
     * @param String $fieldName [Image field name]
     * @param Array $file [File from html input]
     * @param String $directory [Destination directory]
     * @param Array $entry [Current image field data]
     * @param Array $struct [Image field structure]
     * @return String [Picture url from project root]
     */
    protected function savePicture(String $fieldName, Array $file, String $directory, Array $entry = [], Array $struct = []):String {
        $fileName = $this->cleanFileName($fieldName,$file["name"]);
        $destinationPath = $this->getFileUrl(self::FILES_DIRECTORY.$directory."/".$fileName);
        // Resize the picture or save it if resizing is unecessary
        if (key_exists("width",$entry) && key_exists("height",$entry)){
            $img = $this->resize($file["tmp_name"],$entry["width"],$entry["height"],$entry["crop"]);
        } else if (key_exists("width",$struct) && key_exists("height",$struct)) {
            $img = $this->resize($file["tmp_name"],$struct["width"],$struct["height"],$struct["crop"]);
        } else {
            move_uploaded_file($file["tmp_name"],$destinationPath);
        }
        // Save img if she was resized
        if (isset($img)) {
            $this->saveImageInCandideData($img,$destinationPath);  
        }
        // Remove the old file if it exists
        if (key_exists("data",$entry)) {
            $this->deleteFiles(ROOT_DIR.$entry["data"]);
        }
        $this->sendNotification(Notification::NEW_PICTURE_SAVED, [ "url" => $destinationPath ]);
        return "/CandideData/files/".$directory."/".$fileName;
    }

    /**
     * Save an image in CandideData according to its type
     *
     * @param Array $img [Array with type and image fields]
     * @param String $destinationPath [Destination for the image]
     * @return void
     */
    private function saveImageInCandideData(Array $img, String $destinationPath){
        switch($img["type"]) {
            case "image/png":
                imagepng($img["image"], $destinationPath);
                break;
            case "image/gif":
                imagegif($img["image"], $destinationPath);
                break;
            default:
                imagejpeg($img["image"], $destinationPath, 100);
                break;
        } 
    }

    /**
     * Resize an image to specific dimensions 
     *
     * @param String $tmpFileName [Temporary file path]
     * @param Int $width [Result image width]
     * @param Int $height [Result image height]
     * @param Bool $crop [Crop or fit the image to the destination size]
     * @return Array [Which contains image and type]
     */
    private function resize(String $tmpFileName, Int $width, Int $height, Bool $crop):Array {
        $imgSize = getimagesize($tmpFileName);
        $sourceImage = $this->getImage($imgSize,$tmpFileName);
        $newImg = $this->generateCroppedImage($sourceImage,$width,$height,$imgSize,$crop);
        return [
            "image" => $newImg,
            "type" => $imgSize["mime"]
        ];
    }

    /**
     * Crop an image to specific dimensions
     *
     * @param Resource $baseImage [Image to resize]
     * @param Int $destWidth [Result image width]
     * @param Int $destHeight [Result image height]
     * @param Array $imgSize [Base image dimensions]
     * @param Bool $crop [Crop or fit the image to the destination size]
     * @return Resource [Resized image]
     */
    private function generateCroppedImage($baseImage, Int $destWidth, Int $destHeight, Array $imgSize, Bool $crop) {
        $destinationRatio = $destHeight/$destWidth;
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
        $newImg = imagecreatetruecolor($destWidth , $destHeight) or die ("Erreur");
        imagecolortransparent($newImg, null);
        imagealphablending($newImg, false);
        imagecopyresampled($newImg, $baseImage, 0,0, $offsetX, $offsetY, $destWidth, $destHeight, $captureWidth,$captureHeight);
        return $newImg;
    }

    /**
     * Generate a resource according to mime type
     *
     * @param Array $imgInfos [Image informations get thanks to getimagesize() func]
     * @param String $tmpFileName [File path]
     * @return Resource
     */
    private function getImage(Array $imgInfos, String $tmpFileName) {
        switch ($imgInfos["mime"]) {
            case 'image/png':
                return imagecreatefrompng($tmpFileName);
            case 'image/gif':
                return imagecreatefromgif($tmpFileName);
            default:
                return imagecreatefromjpeg($tmpFileName);
        }
    }

    /**
     * Clean file name to be safe to use
     *
     * @param String $fieldName [Field name of the image]
     * @param String $fileName [File name]
     * @return String
     */
    private function cleanFileName(String $fieldName, String $fileName):String {
        $cleanFileName = preg_replace("/[^a-zA-Z0-^._]/", "_", $fileName);
        return strtolower($fieldName."_".time().$cleanFileName);
    }

    /**
     * Remove all files at a specific path
     *
     * @param String $targetedUrl [Path to delete (Directory or file)]
     * @return void
     */
    protected function deleteFiles(String $targetedUrl) {
        // Only allow to delete files into CandideData
        if (strpos($targetedUrl,"/CandideData/") !== false) {
            $targetedUrl = ROOT_DIR.strstr($targetedUrl,"/CandideData/");
            if(is_dir($targetedUrl)){
                $files = glob( $targetedUrl . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
                foreach( $files as $file ){
                    $this->deleteFiles( $file );
                }
                rmdir($targetedUrl);
            } elseif(is_file($targetedUrl)) {
                unlink( $targetedUrl );
            }
        }
    }

}