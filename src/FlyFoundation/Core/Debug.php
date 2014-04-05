<?php

namespace Core;


class Debug {
    private static $messages = array();

    public static function Notice($context, $text){
        self::$messages[] = array(
            "type" => "Notice",
            "message" => $text,
            "context" => get_class($context)
        );
    }

    public static function Warning($context, $text){
        self::$messages[] = array(
            "type" => "Warning",
            "message" => $text,
            "context" => get_class($context)
        );
    }

    public static function Error($context, $text){
        self::$messages[] = array(
            "type" => "Error",
            "message" => $text,
            "context" => get_class($context)
        );
    }

    public static function Flush(){
        foreach(self::$messages as $m){
            echo sprintf("%s: %s in '%s'",$m);
        }
    }
} 