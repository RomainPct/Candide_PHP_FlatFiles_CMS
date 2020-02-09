<?php
/**
 * WysiwygFiles.trait.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Give functions to help file managment for wysiwyg inputs
 * 
 * @since 1.0
 * 
 */
trait WysiwygFiles {

    use FilesManager;

    /**
     * Manage a new picture in wysiwyg field
     *
     * @param String $fieldName [Wysiwyg field name]
     * @param Array $file [File from html input]
     * @param String $directory [Destination directory]
     * @param Array $texts [Field data]
     * @param Array $infos [Field structure]
     * @return String [Picture url from project root]
     */
    protected function saveWysiwygFile(String $fieldName, Array $file, String $directory, Array &$texts, Array $infos): String {
        $pictureUrl = $this->savePicture($fieldName,$file,$directory);
        // Browse data to replace the id by the url
        foreach ($infos as $infosKey => $text){
            if (
                key_exists("wysiwyg",$infos[$infosKey])
                && $infos[$infosKey]["wysiwyg"]
                && key_exists($infosKey,$texts)
                && strpos($texts[$infosKey],$fieldName) !== false
                ) {
                $texts[$infosKey] = str_replace($fieldName,$pictureUrl,$texts[$infosKey]);
                break;
            }
        }
        return $pictureUrl;  
    }

     /**
      * Clean wysiwyg files by deleting useless pictures
      *
      * @param String $instanceName [Instance name]
      * @param Int|null $itemId [Item id if we are in a collection]
      * @param Array $collectionData [Collection data for this item to get wysiwyg element keys]
      * @return void
      */
    protected function cleanWysiwygFiles(String $instanceName, ?Int $itemId = null, Array $collectionData = []) {
        // Get all wysiwyg fields in one string
        $wysiwygData = $this->resumeWysiwygFields($instanceName, $itemId, $collectionData);
        // Get each image url from the wysiwyg fields
        preg_match_all("/<img src=\"([\s\S]+?)\">/", $wysiwygData, $matches,PREG_SET_ORDER);
        $usefullFiles = array_map(function($entry){
            return strstr($entry[1],"/CandideData/"); // Force the path to begin at /CandideData/
        },$matches);
        // Get all images in the current wysiwyg folder
        $wisywigFilesPath = ($itemId !== null) ? $instanceName."/".$itemId : $instanceName;
        $allFiles = glob( Basic::FILES_DIRECTORY.$wisywigFilesPath.'/wysiwyg/*', GLOB_MARK );
        // Remove useless files
        $this->removeUselessFiles($allFiles, $usefullFiles);
    }

    /**
     * Generate a single string of all wysiwyg inputs
     *
     * @param String $instanceName [Instance name]
     * @param Int|null $itemId [Item id if we are in a collection]
     * @param Array $collectionData [Collection data for this item to get wysiwyg element keys]
     * @return String [A single string of all wysiwyg data]
     */
    private function resumeWysiwygFields(String $instanceName, ?Int $itemId = null, Array $collectionData = []):String{
        $wysiwygFieldKeys = array_keys(
            array_filter(
                array_merge($this->_data,$collectionData),
                function($d) {
                    return is_array($d) && $d['type'] == 'text' && $d["wysiwyg"] == true;
                }
            )
        );
        $wysiwygDatas = [];
        $mainPath = ($itemId !== null) ? $instanceName."/items/".$itemId : $instanceName;
        foreach (glob(Basic::DATA_DIRECTORY.$mainPath."/*.json") as $jsonFile) {
            $data = $this->readJsonFile($jsonFile);
            foreach ($wysiwygFieldKeys as $key) {
                if (key_exists($key,$data) && key_exists('data',$data[$key])) {
                    $wysiwygDatas[] = $data[$key]['data'];
                }
            }
        }
        if ($itemId != null) {
            foreach (glob(Basic::DATA_DIRECTORY.$instanceName."/global/*.json") as $jsonFile) {
                $data = $this->readJsonFile($jsonFile)[$itemId];
                foreach ($wysiwygFieldKeys as $key) {
                    if (key_exists($key,$data) && key_exists('data',$data[$key])) {
                        $wysiwygDatas[] = $data[$key]['data'];
                    }
                }
            }   
        }
        return implode($wysiwygDatas);
    }

    /**
     * Clean all useless files
     *
     * @param String[] $allFiles
     * @param String[] $usefullFiles
     * @return void
     */
    private function removeUselessFiles(Array $allFiles, Array $usefullFiles){
        foreach ($allFiles as $file) {
            $isUseless = true;
            foreach ($usefullFiles as $usefullFile) {
                if (strpos($file,$usefullFile) !== false) {
                    $isUseless = false;
                    break;
                }
            }
            if ($isUseless) {
                $this->deleteFiles($file);
            }
        }
    }

}