<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\NotImplementedException;
use FlyFoundation\Util\DirectoryList;

class SystemDefinitionBuilder {

    /**
     * @param DirectoryList $definitionDirectories
     * @return SystemDefinition
     */
    public function buildSystemDefinitionFromDirectories(DirectoryList $definitionDirectories)
    {
        $systemDefinition = new SystemDefinition();
        $this->addDataFromDirectories($definitionDirectories, $systemDefinition);
        $systemDefinition->finalize();
        return $systemDefinition;
    }

    public function buildSystemDefinitionFromDirectory($directory)
    {
        $systemDefinition = new SystemDefinition();
        $this->addDataFromDirectory($directory, $systemDefinition);
        $systemDefinition->finalize();
        return $systemDefinition;
    }

    public function buildSystemDefinitionFromFile($file)
    {
        $systemDefinition = new SystemDefinition();
        $this->addDataFromFile($file, $systemDefinition);
        $systemDefinition->finalize();
        return $systemDefinition;
    }

    private function addDataFromDirectories(DirectoryList $definitionDirectories, SystemDefinition $systemDefinition)
    {
        foreach($definitionDirectories->asArray() as $dir){
            $systemDefinition = $this->addDataFromDirectory($dir, $systemDefinition);
        }
        return $systemDefinition;
    }

    private function addDataFromDirectory($directory, SystemDefinition $systemDefinition)
    {
        if(!is_dir($directory)){
            throw new InvalidArgumentException("The supplied directory is not a valid directory name in the file system");
        }
        $files = scandir($directory);
        $definitionFiles = [];

        foreach($files as $file){
            if(preg_match("/^.+(\\.lsd|\\.json)$/",strtolower($file))){
                $definitionFiles[] = $directory.DIRECTORY_SEPARATOR.$file;
            }
        }

        foreach($definitionFiles as $definitionFile){
            $systemDefinition = $this->addDataFromFile($definitionFile, $systemDefinition);
        }

        return $systemDefinition;
    }

    private function addDataFromFile($file, SystemDefinition $systemDefinition)
    {
        if(!is_file($file)){
            throw new InvalidArgumentException("The supplied file is not a valid file name in the file system");
        }
        $data = $this->readDataFromDefinitionFile($file);
        $systemDefinition->applyOptions($data);
        return $systemDefinition;
    }

    private function readDataFromDefinitionFile($file)
    {
        if(preg_match("/^.+\\.lsd$/",strtolower($file))){
            return $this->readDataFromLsdFile($file);
        }
        if(preg_match("/^.+\\.json$/",strtolower($file))){
            return $this->readDataFromJsonFile($file);
        }
        throw new InvalidArgumentException("The given file '".$file."' has an unknown file-extension. Supported extensions are .lsd and .json");
    }

    private function readDataFromJsonFile($file)
    {
        return json_decode(file_get_contents($file));
    }

    private function readDataFromLsdFile($file)
    {
        throw new NotImplementedException("The parsing of .LSD-files is not yet implemented.");
        return [];
    }
} 