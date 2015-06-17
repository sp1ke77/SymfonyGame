<?php

namespace GameBundle\Utils;

class FrontEndUtils {

    /**
     * Converts a query-string (e.g. JQuery's $.post) into an associative awway
     *
     * @arg string $query
     * @arg int|bool $checkArgs
     * @return array
     */
    public function parseQueryString($query, $checkArgs = false)
    {
        $result = [];
        $formData = explode('&', $query);
        if (($checkArgs) && (count($formData) != $checkArgs))
        {
            return null;
        }
        foreach ($formData as $string) {
            $field = explode('=', $string);
            $result[$field[0]] = $field[1];
        }
        return $result;
    }
}