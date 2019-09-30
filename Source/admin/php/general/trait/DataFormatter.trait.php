<?php

trait DataFormatter {
    
    /**
     * Format an element
     *
     * @param Array $element [Element structure and data]
     * @return String
     */
    protected function formatElement(Array $element):String {
        switch($element['type']) {
            case "text":
                return $this->formatTextElement($element);
                break;
            case "number":
                return $this->formatNumberElement($element);
                break;
            default:
                return $this->formatOtherElement($element);
                break;
        }
    }

    /**
     * Format a text element
     *
     * @param Array $element [Element structure and data]
     * @return String
     */
    private function formatTextElement(Array $element):String {
        return str_replace("\r\n","<br>",$element["data"]);
    }

    /**
     * Format a number element
     *
     * @param Array $element [Element structure and data]
     * @return String
     */
    private function formatNumberElement(Array $element):String {
        $fmt = new NumberFormatter(LOCALE, $element["format"]);
        return $fmt->format($element["data"]);
    }

    /**
     * Format element if a formatter is found OR return the element data
     *
     * @param Array $element [Element structure and data]
     * @return String
     */
    private function formatOtherElement(Array $element):String {
        $customMethodName = "format".$element["type"]."Element";
        if (key_exists($customMethodName,$this->_methods)) {
            return $this->_methods[$customMethodName]($element);
        }
        return $element["data"];
    }

}
