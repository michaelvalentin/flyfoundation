<?php
namespace Util;

/**
 * Class Objectifier
 *
 * Class for turning structures into object-like array, suitable for Mustache and similar systems.
 *
 * @package Util
 */
class Objectifier {

    /**
     * Turn an array into an object like array structure. Can be done recursively.
     *
     * @param $array
     * @param bool $recursive
     * @return array
     */
    public static function Objectify($array, $recursive = true){
        $result = array();
        foreach($array as $l=>$v){
            $value = $recursive && is_array($v) ? self::Jsonify($array,true) : $v;
            $label = $l;
            $result[] = array(
                "label" => $label,
                "value" => $value
            );
        }
        return $result;
    }
} 