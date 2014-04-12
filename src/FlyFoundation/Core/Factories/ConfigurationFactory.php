<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Config;
use FlyFoundation\Configurator;
use FlyFoundation\Util\DirectoryList;

class ConfigurationFactory {
    /** @var \FlyFoundation\Config  */
    private $config;

    /** @var \FlyFoundation\Util\DirectoryList  */
    private $configuratorDirectories;

    public function __construct(Config $config){
        $this->config = $config;
        $this->configuratorDirectories = new DirectoryList();
    }

    public function getConfiguration(){
        $config = $this->applyConfigurators($this->config);
        return $config;
    }

    public function addConfiguratorDirectory($directory){
        $this->configuratorDirectories->add($directory);
    }

    private function applyConfigurators(Config $config)
    {
        foreach($this->getConfigurators() as $configurator)
        {
            /** @var Configurator $configurator */
            $config = $configurator->apply($config);
        }
        return $config;
    }

    private function getConfigurators()
    {
        $configurators = [];

        foreach($this->configuratorDirectories->asArray() as $directory)
        {
            $directoryConfigurators = $this->readConfiguratorDirectory($directory);
            $configurators = array_merge($configurators, $directoryConfigurators);
        }

        return $configurators;
    }

    private function readConfiguratorDirectory($directory)
    {
        $files = scandir($directory);
        $configurators = [];

        foreach($files as $file){
            $configurator = $this->configuratorFromFile($file,$directory);

            if($configurator){
                $configurators[] = $configurator;
            }
        }

        return $configurators;
    }

    private function configuratorFromFile($file, $directory)
    {
        $matches = [];
        $phpFile = preg_match("/^(.*)(\.php)$/",$file,$matches);

        if(!$phpFile){
            return false;
        }

        require $directory."/".$file;

        $className = $matches[1];
        if(!class_exists($className)){
            return false;
        }

        return new $className();
    }
} 