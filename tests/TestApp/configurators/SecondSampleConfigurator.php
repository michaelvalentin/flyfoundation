<?php


class SecondSampleConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->set("test2","This is a demo");
        $config->set("test3","Something else..");
        return $config;
    }
}