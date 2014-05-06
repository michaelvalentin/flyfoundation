<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Config;
use FlyFoundation\Core\Environment;
use FlyFoundation\Core\FileLoader;
use FlyFoundation\Exceptions\InvalidConfigurationException;
use FlyFoundation\Exceptions\InvalidJsonException;
use FlyFoundation\Exceptions\NotImplementedException;
use FlyFoundation\SystemDefinitions\SystemDefinition;

class SystemDefinitionFactory {

    use Environment;

    /**
     * @param \FlyFoundation\Config $config
     * @return SystemDefinition
     */
    public function loadFromConfig(Config $config){
        $definitionData = $this->systemDefinitionSkeletonFromConfig($config);
        $definitionData["entities"] = $this->entitiesFromConfig($config);
        $systemDefinition = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\SystemDefinition");
        $systemDefinition->applyOptions($definitionData);
        $systemDefinition->applyOptions($definitionData);
        $systemDefinition->finalize();
        return $systemDefinition;
    }

    private function systemDefinitionSkeletonFromConfig(Config $config)
    {
        $result = [];
        $result["name"] = $config->get("app_name");
        $settings = $config->get("app_settings");
        if(!$settings){
            $settings = [];
        }
        $result["settings"] = $settings;

        return $result;
    }

    private function entitiesFromConfig(Config $config)
    {
        $result = [];
        foreach($config->entityDefinitions->asArray() as $entityName){
            $result[] = $this->loadEntityFile($entityName);
        }
        return $result;
    }

    private function loadEntityFile($entityName)
    {
        /** @var FileLoader $fileLoader */
        $fileLoader = $this->getFactory()->load("\\FlyFoundation\\Core\\FileLoader");
        $fileName = $fileLoader->findEntityDefinition($entityName);
        if(!$fileName){
            throw new InvalidConfigurationException("No entity named '".$entityName."' could be found.");
        }

        $result = json_decode(file_get_contents($fileName),true);
        if($result === null){
            throw new InvalidJsonException("The JSON data in the file: ".$fileName." could not be parsed as valid JSON-data.");
        }

        if(isset($result["extends"])){
            foreach($result["extends"] as $parentValue){
                $entityOptions = $this->loadEntityFile($parentValue);
                $result = array_merge($entityOptions, $result);
            }
            unset($result["extends"]);
        }

        return $result;
    }


} 