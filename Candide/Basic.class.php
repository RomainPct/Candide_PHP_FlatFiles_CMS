<?php


class Basic {

    const DATA_DIRECTORY = ROOT_DIR."/CandideData/content/";
    const FILES_DIRECTORY = ROOT_DIR."/CandideData/files/";

    protected function formatTitle(String $title, Bool $removingFirstPart = false):String {
        if ($removingFirstPart) {
            $title = ltrim(strstr($title,"_"),"_");
        }
        return ucfirst(str_replace("_"," ",$title));
    }

    protected function formatText(String $text):String {
        return str_replace("\r\n","<br>",$text);
    }

}