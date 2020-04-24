<?php
/**
 * ElementGetters.trait.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Provide element getters for default candide field types and handle other
 * 
 * @since 1.0
 * 
 */
trait ElementGetters {

    use DataFormatter;

    /**
     * Generate a text field on admin platform
     * Echo text
     *
     * @param String $title [Field title for admin interface]
     * @param Bool $wysiwyg [Option to enable a wysiwyg editor in admin interface]
     * @return void
     */
    public function text(String $title, Bool $wysiwyg = false){
        $this->getElement($title,'text',['wysiwyg'=>$wysiwyg]);
    }

    /**
     * Generate an image field on admin platform
     * Echo image url from root directory on front end
     *
     * @param String $title [Field title for admin interface]
     * @param Array $size [ [Width,Height] of your image, Candide automatically resize the input image]
     * @param Bool $crop [If you pass crop to false, the image will fit the size you defined]
     * @return void
     */
    public function image(String $title, Array $size, Bool $crop = true){
        $this->getElement($title,'image',[
            'width'=>$size[0],
            'height'=>$size[1],
            'crop' => $crop
            ]);
    }

    /**
     * Generate a number field on admin platform
     * Echo formatted number
     *
     * @param String $title [Field title for admin interface]
     * @param Int $format [Echo formatting method]
     * @return void
     */
    public function number(String $title, Int $format = NumberFormatter::DECIMAL){
        $this->getElement($title,'number',['format'=>$format]);
    }

    /**
     * Manage element update if needed
     * Echo formatted data
     *
     * @param String $title [Field title]
     * @param String $type [Field type]
     * @param Array $options [An array of custom options]
     * @return void
     */
    protected function getElement(String $title,String $type,Array $options) {
        $name = $type.'_'.$title;
        // Gérer l'update
        if ($this->_updateCall) {
            $this->manageUpdate($name,$type,$options);
        }
        // Gérer l'affichage
        if (array_key_exists($name,$this->_data) && array_key_exists('data',$this->_data[$name])) {
            echo $this->formatElement($this->_data[$name]);
        } else {
            echo 'Update candide on the admin platform';
        }
    }

    public function setElementFromConfigFile(String $title, Array $data) {
        $name = $data['type'].'_'.$title;
        // Gérer l'update
        if ($this->_updateCall) {
            $this->manageUpdate($name, $data['type'], $data['options'] ?? []);
        }
    }

    /**
     * Default manage update function
     *
     * @param String $name [Field name]
     * @param String $type [Field type]
     * @param Array $options [Options]
     * @return void
     */
    protected function manageUpdate(String $name, String $type, Array $options){
        $this->__call('manageUpdate',[$name,$type,$options]);
    }

}