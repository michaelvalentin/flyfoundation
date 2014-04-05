<?php

namespace Util;


use Exceptions\InvalidArgumentException;

class Profiler {
    private static $timers = array();

    /**
     * Start a timer
     *
     * @param $label
     * @param $description
     */
    public static function StartTimer($label, $description){
        self::$timers[$label] = array(
            "time_start" => microtime(true),
            "description" => $description
        );
    }

    /**
     * Stop a timer
     *
     * @param $label
     * @throws \Exceptions\InvalidArgumentException
     */
    public static function StopTimer($label){
        if(!isset(self::$timers[$label])){
            throw new InvalidArgumentException("No timer with the label ".$label." exists");
        }
        self::$timers[$label]["time_end"] = microtime(true);
    }

    /**
     * Print a timer, showing the elapsed time. The timer MUST exist.
     *
     * @param $label
     * @throws \Exceptions\InvalidArgumentException
     */
    public static function PrintTimer($label){
        if(!isset(self::$timers[$label])){
            throw new InvalidArgumentException("No timer with the label ".$label." exists");
        }
        if(!isset(self::$timers[$label]["time_end"])){
            self::$timers[$label]["time_end"] = microtime(true);
        }
        $time = self::$timers[$label]["time_end"] - self::$timers[$label]["time_start"];
        $desc = self::$timers[$label]["description"];
        echo sprintf('<p>The timer <b>'.$label.'</b> elapsed <b>%f</b> seconds ('.$desc.')</p>',$time);
    }

    /**
     * Print all timers in the profiler
     */
    public static function PrintAllTimers(){
        foreach(self::$timers as $label => $value){
            self::PrintTimer($label);
        }
    }

    /**
     * Get all the timers as an array
     *
     * @return array
     */
    public static function GetTimers(){
        return self::$profilers;
    }
} 