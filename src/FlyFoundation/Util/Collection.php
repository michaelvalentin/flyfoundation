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
     * @param $element
     * @return bool
     */
    public function contains($element);

    public function clear();

    /**
     * @return array
     */
    public function asArray();
} 