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
use FlyFoundation\SystemDefinitions\SystemDefinitionBuilder;
use FlyFoundation\Util\DirectoryList;

class SystemDefinitionFactory {

    /** @var \FlyFoundation\Util\DirectoryList  */
    private $directiveDirectories;

    public function __construct()
    {
        $this->directiveDirectories = new DirectoryList();
    }

    public function addDirectiveDirectory($directory)
    {
        $this->directiveDirectories->add($directory);
    }

    /**
     * @return SystemDefinition
     */
    public function getSystemDefinition(){
        $systemDefinitionBuilder = new SystemDefinitionBuilder();
        return $systemDefinitionBuilder->buildSystemDefinitionFromDirectories($this->directiveDirectories);
    }

} 