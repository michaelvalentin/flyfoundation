<?php


namespace FlyFoundation\Core;


use FlyFoundation\Controllers\Controller;

class DefaultRouter implements Router{

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * @param string $query
     * @return Controller
     */
    public function getController($query)
    {
        // TODO: Implement getController() method.
    }

    /**
     * @param string $query
     * @return Map
     */
    public function getArguments($query)
    {
        // TODO: Implement getArguments() method.
    }
}