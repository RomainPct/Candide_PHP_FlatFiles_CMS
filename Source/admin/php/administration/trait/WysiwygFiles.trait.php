<?php

trait WysiwygFiles {

    use FilesManager;

    protected function saveWysiwygFile($key,$file,$dest,&$texts,$infos): String {
        $url = $this->savePicture($key,$file,$dest);
        // Browse data to replace the id by the url
        foreach ($infos as $infosKey => $text){
            if (
                key_exists("wysiwyg",$infos[$infosKey])
                && $infos[$infosKey]["wysiwyg"]
                && key_exists($infosKey,$texts)
                && strpos($texts[$infosKey],$key) !== false
                ) {
                $texts[$infosKey] = str_replace($key,$url,$texts[$infosKey]);
                break;
            }
        }
        return $url;  
    }

    protected function removeWysiwygFiles($elementFolderUrl,$collectionData = []) {
        // Get all wysiwyg fields in one string
        $wysiwygData = $this->resumeWysiwygFields($collectionData);
        // Get each image url from the wysiwyg fields
        preg_match_all("/<img src=\"([\s\S]+?)\">/", $wysiwygData, $matches,PREG_SET_ORDER);
        $usefullFiles = array_map(function($entry){
            return strstr($entry[1],"/CandideData/"); // Force the path to begin at /CandideData/
        },$matches);
        // Get all images in the current wysiwyg folder
        $allFiles = glob( $elementFolderUrl.'/wysiwyg/*', GLOB_MARK );
        // Remove useless files
        $this->removeUselessFiles($allFiles, $usefullFiles);
    }

    private function removeUselessFiles($allFiles, $usefullFiles){
        foreach ($allFiles as $file) {
            $isUseless = true;
            foreach ($usefullFiles as $usefullFile) {
                if (strpos($file,$usefullFile) !== false) {
                    $isUseless = false;
                    break;
                }
            }
            // If file is useless -> delete it
            if ($isUseless) {
                $this->deleteFiles($file);
            }
        }
    }

    private function resumeWysiwygFields(Array $collectionData){
        return implode(
            array_column(
                array_filter(
                    array_merge($this->_data,$collectionData),
                    function($d){
                        return $d["type"] == "text" && $d["wysiwyg"] === true;
                    }
                )
            ,"data")
        );
    }

}