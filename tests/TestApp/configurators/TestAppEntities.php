<?php


class TestAppEntities implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->entityDefinitions->addAll([
            "DemoEntity",
            "MyImage",
            "MyModel"
        ]);
        return $config;
    }
}