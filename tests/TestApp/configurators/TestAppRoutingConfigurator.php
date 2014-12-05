<?php


use FlyFoundation\Core\Config;

class TestAppRoutingConfigurator implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $config->routing->addRouting("GET:", "TestAppSpecial#showFrontPage");
        $config->routing->addRouting("GET:mytest/{alias}", "TestAppSpecial#show");
        $config->routing->addRouting("POST:mytest/delete/{id}", "TestAppSpecial#delete");
        $config->routing->addRouting("GET:{alias}", "TestAppSpecial#showAll");
        return $config;
    }
}