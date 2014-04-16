<?php


namespace FlyFoundation\Core;


use FlyFoundation\Controllers\Controller;
use FlyFoundation\Controllers\PageController;

class StandardRouter implements Router{

    use Environment;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->setContext($context);
    }

    /**
     * @param string $query
     * @return Controller
     */
    public function getController($query)
    {
        return $this->getFactory()->load("\\FlyFoundation\\Controllers\\PageController");
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