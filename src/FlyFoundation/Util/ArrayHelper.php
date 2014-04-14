<?php


namespace FlyFoundation\Util;


class ArrayHelper {

    public static function AssociativeArrayToObjectStyleArray(array $array, $recursive = true){
        $result = array();
        foreach($array as $label=>$value){
            $value = $recursive && is_array($value) ? self::AssociativeArrayToObjectStyleArray($array,true) : $value;
            $result[] = array(
                "label" => $label,
                "value" => $value
            );
        }
        return $result;
    }

} 