<?php


namespace FlyFoundation\Core;


use FlyFoundation\Controllers\Controller;

interface Router {
    /**
     * @param Context $context
     */
    public function __construct(Context $context);

    /**
     * @param string $query
     * @return Controller
     */
    public function getController($query);

    /**
     * @param string $query
     * @return Map
     */
    public function getArguments($query);
} 