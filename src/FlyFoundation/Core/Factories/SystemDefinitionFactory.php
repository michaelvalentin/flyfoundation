<?php


namespace FlyFoundation\Core\Factories;


use FlyFoundation\Core\Config;
use FlyFoundation\Core\Environment;
use FlyFoundation\Core\FileLoader;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\InvalidConfigurationException;
use FlyFoundation\Exceptions\InvalidJsonException;
use FlyFoundation\Exceptions\NotImplementedException;
use FlyFoundation\Factory;
use FlyFoundation\LsdParser\LsdParser;
use FlyFoundation\SystemDefinitions\SystemDefinition;
use FlyFoundation\SystemDefinitions\SystemDefinitionBuilder;
use FlyFoundation\Util\DirectoryList;

class SystemDefinitionFactory {

    /** @var \FlyFoundation\Util\DirectoryList  */
    private $definitionDirectories;

    public function __construct()
    {
        $this->definitionDirectories = new DirectoryList();
    }

    public function addDirectiveDirectory($directory)
    {
        $this->definitionDirectories->add($directory);
    }

    public function setDefinitionDirectories(DirectoryList $directories)
    {
        $this->definitionDirectories = $directories;
    }

    /**
     * @return SystemDefinition
     */
    public function getSystemDefinition(){
        $lsdParser = new LsdParser();
        foreach($this->definitionDirectories->asArray() as $directory){
            $files = scandir($directory);
            foreach($files as $file){
                if(preg_match("/^.+\.lsd$/i",$file)){
                    $filePath = $directory."/".$file;
                    $lsdParser->addFile($filePath);
                }
            }
        }
        return $lsdParser->getSystemDefinition();
    }

} 