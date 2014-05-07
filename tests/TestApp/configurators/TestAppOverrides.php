<?php


class TestAppOverrides implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->classOverrides->putAll([
            "\\FlyFoundation\\FooBarClass" => "\\TestApp\\SomeClass",
            "\\TestApp\\SomeClass" => "\\TestApp\\DemoClass"
        ]);
        return $config;
    }
}