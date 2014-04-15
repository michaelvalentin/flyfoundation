<?php


namespace FlyFoundation\Core;

use FlyFoundation\Exceptions\NonExistantFileException;

class StandardFileLoader implements FileLoader{

    use Environment;

    public function findFile($path)
    {
        $baseDirectories = array_reverse($this->getConfig()->baseFileDirectories->asArray());

        $result = $this->findSpecialFile($path);

        if(!$result){
            $result = $this->findFileInPaths($path, $baseDirectories);
        }

        if(!$result){
            throw new NonExistantFileException("The file '".$path."' could not be located in the given include paths");
        }

        return $result;
    }

    public function findTemplate($name)
    {
        return $this->findFile("templates/".$name.".mustache");
    }

    public function findEntityDefinition($name)
    {
        return $this->findFile("entity_definitions/".$name.".json");
    }

    private function findFileInPaths($name, $paths)
    {
        foreach($paths as $path)
        {
            $filename = $path."/".$name;
            if(file_exists($filename)){
                return $filename;
            }
        }
        return false;
    }

    private function findSpecialFile($path)
    {
        $specialFilePattern = "/^(templates|entity_definitions)\\/(.*)$/";
        $matches = [];
        $isSpecialFile = preg_match($specialFilePattern, $path, $matches);

        if(!$isSpecialFile){
            return false;
        }

        switch($matches[1]){
            case "templates" :
                $paths = $this->getConfig()->templateDirectories->asArray();
                break;
            case "entity_definitions" :
                $paths = $this->getConfig()->entityDefinitionDirectories->asArray();
                break;
            default :
                $paths = [];
                break;
        }

        $paths = array_reverse($paths);

        return $this->findFileInPaths($matches[2],$paths);
    }
}