<?php

namespace Flyf\Util;

/**
 * TODO: Write class description
 * 
 * @Package: Flyf\Util
 * @Author: Michael Valentin
 * @Created: 06-08-13 - 14:25
 */
class Session extends Implementation{
    private $_started = false;

    /**
     * Get a session object (Singleton)
     *
     * @return Session
     */
    public static function I(){
        return parent::I();
    }

    private function startSession(){
        if($this->_started) return;
        session_start();
        $this->_started = true;
    }

    /**
     * Set a session value
     *
     * @param string $key
     * @param string $value
     */
    public function Set($key, $value){
        $this->startSession();
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     *
     * @param $key
     * @return string|null
     */
    public function Get($key){
        $this->startSession();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Destroy the session!
     */
    public function Destroy(){
        $this->startSession();
        session_destroy();
    }
}