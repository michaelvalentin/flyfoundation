<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Config;
use FlyFoundation\Core\Environment;
use FlyFoundation\Core\FileLoader;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidConfigurationException;
use FlyFoundation\Exceptions\InvalidJsonException;
use FlyFoundation\Exceptions\NotImplementedException;
use FlyFoundation\Factory;
use FlyFoundation\SystemDefinitions\SystemDefinition;

class SystemDefinitionFactory {

    use AppConfig;

    /**
     * @param \FlyFoundation\Config $config
     * @return SystemDefinition
     */
    public function createDefinition(){
        $definitionData = $this->systemDefinitionSkeletonFromConfig();
        $definitionData["entities"] = $this->entitiesFromConfig();
        $systemDefinition = new SystemDefinition();
        $systemDefinition->applyOptions($definitionData);
        $systemDefinition->applyOptions($definitionData);
        $systemDefinition->finalize();
        return $systemDefinition;
    }

    private function systemDefinitionSkeletonFromConfig()
    {
        $result = [];
        $result["name"] = $this->getAppConfig()->get("app_name");
        $settings = $this->getAppConfig()->get("app_settings");
        if(!$settings){
            $settings = [];
        }
        $result["settings"] = $settings;

        return $result;
    }

    private function entitiesFromConfig()
    {
        $result = [];
        foreach($this->getAppConfig()->entityDefinitions->asArray() as $entityName){
            $result[] = $this->loadEntityFile($entityName);
        }
        return $result;
    }

    private function loadEntityFile($entityName)
    {
        /** @var FileLoader $fileLoader */
        $fileLoader = Factory::load("\\FlyFoundation\\Core\\StandardFileLoader");
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