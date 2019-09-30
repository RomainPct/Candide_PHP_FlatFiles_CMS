<?php

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
