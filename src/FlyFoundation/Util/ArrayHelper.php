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

    public static function IndentedLinesToTreeArray(array $lineArrays, callable $getIndent = null, $childrenFieldName = "children")
    {
        //Default function to get element indentation
        if($getIndent == null){
            $getIndent = function($line){
                return $line["indent"];
            };
        }

        $lineCount = count($lineArrays);

        if($lineCount >= 2)
        {
            $firstLine = array_shift($lineArrays);
            $children = [];
            $nextLine = $lineArrays[0];

            while($getIndent($firstLine) < $getIndent($nextLine)){
                $children[] = array_shift($lineArrays);
                if(!isset($lineArrays[0])){
                    break;
                }
                $nextLine = $lineArrays[0];
            }

            $firstLine[$childrenFieldName] = self::IndentedLinesToTreeArray($children, $getIndent, $childrenFieldName);

            if(count($lineArrays)){
                return array_merge([$firstLine],self::IndentedLinesToTreeArray($lineArrays, $getIndent, $childrenFieldName));
            }else{
                return [$firstLine];
            }
        }
        elseif($lineCount == 1)
        {
            $lineArrays[0][$childrenFieldName] = [];
            return $lineArrays;
        }
        else
        {
            return [];
        }
    }

} 