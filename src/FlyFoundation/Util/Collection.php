<?php


namespace FlyFoundation\Util;


interface Collection {
    /**
     * @return int
     */
    public function size();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return array
     */
    public function asArray();
} 