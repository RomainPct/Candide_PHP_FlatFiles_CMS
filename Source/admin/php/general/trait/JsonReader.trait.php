<?php

trait JsonReader {

    protected function readJsonFile(String $url):Array {
        return file_exists($url) ? json_decode(file_get_contents($url),true) : [];
    }

}
