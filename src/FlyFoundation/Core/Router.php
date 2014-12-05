<?php


namespace FlyFoundation\Core;


use FlyFoundation\Controllers\Controller;

interface Router {


    /**
     * @param Context $context
     * @return SystemQuery
     */
    public function getSystemQuery();
} 