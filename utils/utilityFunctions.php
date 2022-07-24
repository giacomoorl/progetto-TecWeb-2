<?php

namespace UtilityFunctions;

class UtilityFunctions {

    public static function replace($url, $array) {
        $html = file_get_contents($url);
        return UtilityFunctions::replaceFromHTML($html, $array);
    }

    public static function replaceFromHTML($html, $array) {
        foreach ($array as $key => $value)
            $html = str_replace($key, $value, $html);
        return $html;
    }
}

?>