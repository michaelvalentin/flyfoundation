<?php


class TestAppRoutingConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->routing->addRouting("GET:", "TestAppSpecial#showFrontPage");
        $config->routing->addRouting("GET:mytest/{alias}", "TestAppSpecial#show");
        $config->routing->addRouting("POST:mytest/delete/{id}", "TestAppSpecial#delete");
        $config->routing->addRouting("GET:{alias}", "TestAppSpecial#showAll");
        return $config;
    }
}