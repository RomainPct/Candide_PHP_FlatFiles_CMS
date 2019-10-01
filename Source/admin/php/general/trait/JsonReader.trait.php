<?php
/**
 * JsonReader.trait.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Helper for json file reading
 * 
 * @since 1.0
 * 
 */
trait JsonReader {

    /**
     * Read a json file if file exists
     *
     * @param String $url [File url]
     * @return Array
     */
    protected function readJsonFile(String $url):Array {
        return file_exists($url) ? json_decode(file_get_contents($url),true) : [];
    }

}
