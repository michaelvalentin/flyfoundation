<?php


namespace FlyFoundation\Core;


use FlyFoundation\Config;
use FlyFoundation\Factory;

trait Environment {
    /**
     * @var Context
     */
    private $context;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param \FlyFoundation\Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return \FlyFoundation\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \FlyFoundation\Core\Context $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return \FlyFoundation\Core\Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param \FlyFoundation\Factory $factory
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \FlyFoundation\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }


} 