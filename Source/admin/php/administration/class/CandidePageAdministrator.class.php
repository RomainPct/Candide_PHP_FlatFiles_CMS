<?php
/**
 * CandidePageAdministrator.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Manager for Page data update on admin side
 * 
 * @since 1.0
 * Basic < CandideBasic < CandidePageAdministrator
 * 
 */
class CandidePageAdministrator extends CandideBasic {

    use FieldsGenerator, WysiwygFiles;

    /**
     * Echo all field of the current Candide Page
     *
     * @return void
     */
    public function getFields() {
        foreach ($this->_data as $key => $value){
            echo $this->getField($key,$value['data'],$value);
        }
    }

    /**
     * Update page
     *
     * @param Array $texts [Inputs values]
     * @param Array $files [File inputs values]
     * @param Bool $cleanWysiwyg [Bool to choose if you clean useless wysiwyg files or not]
     * @return void
     */
    public function setData(Array $texts, Array $files, Bool $cleanWysiwyg = true) {
        $this->setImages($files, $texts, $this->_data);
        $this->setTexts($texts);
        $this->saveData();
        if ($cleanWysiwyg) {
            $this->cleanWysiwygFiles($this->getInstanceName());
        }
    }

    /**
     * Update texts of the page
     *
     * @param Array $texts [Inputs values]
     * @return void
     */
    protected function setTexts(Array $texts) {
        foreach ($texts as $key => $text){
            if (key_exists($key,$this->_data)) {
                if ($this->_data[$key]['type'] != "image") {
                    $this->_data[$key]['data'] = $text;
                }
            }
        }
    }

    /**
     * Update images of the page
     *
     * @param Array $files [File inputs values]
     * @param Array $texts [Inputs values by reference]
     * @param Array $infos [Current data of the page]
     * @return void
     */
    private function setImages(Array $files, Array &$texts, Array $infos){
        foreach ($files as $fieldName => $file) {
            if ($file["size"] != 0 && strpos($fieldName,"image_") === 0) {
                $this->_data[$fieldName]['data'] = $this->savePicture($fieldName,$file,$this->getInstanceName(),$this->_data[$fieldName]);
            } else if ($file["size"] != 0) {
                $url = $this->saveWysiwygFile($fieldName,$file,$this->getInstanceName()."/wysiwyg",$texts,$infos);
            }
        }
    }

}