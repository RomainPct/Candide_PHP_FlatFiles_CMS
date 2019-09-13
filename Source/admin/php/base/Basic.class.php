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

    protected function formatElement(Array $element):String {
        switch($element['type']) {
            case "text":
                return str_replace("\r\n","<br>",$element["data"]);
                break;
            case "number":
                $fmt = new NumberFormatter(LOCALE, $element["format"]);
                return $fmt->format($element['data']);
                break;
            default:
                return $element["data"];
        }
    }

}