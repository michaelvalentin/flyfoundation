<?php


namespace FlyFoundation\Core;

use FlyFoundation\Exceptions\NonExistantFileException;

class StandardFileLoader implements FileLoader{

    use Environment;

    public function findFile($path)
    {
        $baseDirectories = $this->getConfig()->baseFileDirectories->asArray();

        $result = $this->findSpecialFile($path);

        if(!$result){
            $result = $this->findFileInPaths($path, $baseDirectories);
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

    public function findPage($name)
    {
        return $this->findFile("pages/".$name.".mustache");
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
        $specialFilePattern = "/^(templates|entity_definitions|pages)\\/(.*)$/";
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
            case "pages" :
                $paths = $this->getConfig()->pageDirectories->asArray();
                break;
            default :
                $paths = [];
                break;
        }

        $paths = $paths;

        return $this->findFileInPaths($matches[2],$paths);
    }
}