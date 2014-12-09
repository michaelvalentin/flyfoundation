<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidClassException;
use FlyFoundation\Exceptions\UnknownClassException;
use FlyFoundation\Factory;

class DatabaseFactory extends AbstractFactory
{

    public function load($className, array $arguments = [])
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
            "/(.+)DataMapper$/" => "DataMapperFactory",
            "/(.+)DataFinder$/" => "DataFinderFactory",
            "/(.+)DataStore$/" => "DataStoreFactory",
            "/(.+)DataMethods$/" => "DataMethodsFactory",
            "/(.+)$/" => "DataMapperFactory",
        ];

        $result = false;

        foreach($factories as $regexp => $factoryName){
            if(!$result && preg_match($regexp,$className)){
                $result = Factory::load("\\FlyFoundation\\Core\\Factories\\".$factoryName);
            }
        }

        /** @var AbstractFactory $result */
        $result->setSearchPaths($this->searchPaths);
        return $result;
    }
}