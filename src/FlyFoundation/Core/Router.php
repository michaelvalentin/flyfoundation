<?php


namespace FlyFoundation\Core;


use FlyFoundation\Controllers\Controller;

interface Router {


    /**
     * @param $query
     * @return SystemQuery
     */
    public function getSystemQuery($query);
} 