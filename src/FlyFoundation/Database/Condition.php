<?php

namespace FlyFoundation\Database;


interface Condition
{
    /**
     * @return string
     */
    public function getString();

    /**
     * @return array
     */
    public function getValues();
} 