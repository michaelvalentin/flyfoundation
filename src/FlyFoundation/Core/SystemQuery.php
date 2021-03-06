<?php


namespace FlyFoundation\Core;


use Aws\Common\Exception\InvalidArgumentException;
use FlyFoundation\App;
use FlyFoundation\Controllers\Controller;
use FlyFoundation\Dependencies\AppResponse;

class SystemQuery {

    use AppResponse;

    /** @var Controller */
    private $controller;
    /** @var string */
    private $method;
    /** @var array */
    private $arguments;

    public function execute()
    {
        $controller = $this->controller;
        $controller->render($this->method, $this->arguments);
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    public function addArgument($key, $value)
    {
        $this->arguments[$key] = $value;
    }

    /**
     * @param \FlyFoundation\Controllers\Controller $controller
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return \FlyFoundation\Controllers\Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
} 