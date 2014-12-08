<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Exceptions\UnknownClassException;
use FlyFoundation\Factory;

class DatabaseFactory extends AbstractFactory
{

    public function load($className, $arguments = [])
    {
        $factory = $this->getFactory($className);
        return $factory->load($className, $arguments);
    }

    public function exists($className)
    {
        $factory = $this->getFactory($className);
        return $factory->exists($className);
    }

    protected function prepareGeneric($result, $entityName)
    {
        // Not relevant here...
    }

    private function getFactory($className)
    {
        $factories = [
            "/(*+)DataMapper/" => "DataMapperFactory",
            "/(*+)DataFinder/" => "DataMapperFinder",
            "/(*+)DataStore/" => "DataMapperStore",
        ];

        foreach($factories as $regexp => $factoryName){
            if(preg_match($regexp,$className)){
                return Factory::load("\\FlyFoundation\\Core\\Factories\\".$factoryName);
            }
        }
        return Factory::load("\\FlyFoundation\\Core\\Factories\\".$factories[0]);
    }
}