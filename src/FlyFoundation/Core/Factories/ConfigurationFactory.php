<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Core\Config;
use FlyFoundation\Core\Configurator;
use FlyFoundation\Util\DirectoryList;

class ConfigurationFactory{

    /** @var \FlyFoundation\Core\Config  */
    private $baseConfig;

    /** @var \FlyFoundation\Util\DirectoryList  */
    private $configuratorDirectories;

    public function __construct(Config $config = null){
        if($config === null){
            $this->baseConfig = new Config();
        }else{
            $this->baseConfig = $config;
        }
        $this->configuratorDirectories = new DirectoryList();
    }

    public function getConfiguration(){
        $config = clone $this->baseConfig;
        $config = $this->applyConfigurators($config);
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
        $phpFile = preg_match("/^(.*)(\\.php)$/",$file,$matches);

        if(!$phpFile){
            return false;
        }

        require_once $directory."/".$file;

        $className = $matches[1];
        if(!class_exists($className)){
            return false;
        }

        $instance = new $className();

        if(!$instance instanceof Configurator){
            return false;
        }

        return $instance;
    }
} 