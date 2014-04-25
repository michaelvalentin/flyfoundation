<?php


namespace FlyFoundation\Core;

use FlyFoundation\Exceptions\NonExistantFileException;

class StandardFileLoader implements FileLoader{

    use Environment;

    public function findFile($fileName, array $extensions = [])
    {
        $result = $this->findSpecialFile($fileName, $extensions);

        if(!$result){
            $baseDirectories = $this->getConfig()->baseFileDirectories->asArray();
            $result = $this->findFileInPaths($fileName, $baseDirectories, $extensions);
        }

        return $result;
    }

    public function findTemplate($name)
    {
        return $this->findFile("templates/".$name.".mustache");
    }

    public function findEntityDefinition($name)
    {
        $extensions = [".lsd", ".yml", ".json"];

        return $this->findFile("entity_definitions/".$name, $extensions);
    }

    public function findPage($name)
    {
        return $this->findFile("pages/".$name.".mustache");
    }

    private function findFileInPaths($name, $paths, array $extensions = [])
    {
        if(count($extensions) == 0){
            $extensions = [""];
        }

        foreach($paths as $path)
        {
            $fileName = $this->fileExistsWithExtensions($path."/".$name,$extensions);
            if($fileName){
                return $fileName;
            }
        }
        return false;
    }



    private function findSpecialFile($fileName, array $extensions = [])
    {
        $specialFilePattern = "/^(templates|entity_definitions|pages)\\/(.*)$/";
        $matches = [];
        $isSpecialFile = preg_match($specialFilePattern, $fileName, $matches);

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

        return $this->findFileInPaths($matches[2],$paths, $extensions);
    }

    private function fileExistsWithExtensions($fileName, $extensions)
    {
        foreach($extensions as $extension)
        {
            $fileNameWithExtension = $fileName.$extension;
            if(file_exists($fileNameWithExtension)){
                return $fileNameWithExtension;
            }
        }
        return false;
    }
}