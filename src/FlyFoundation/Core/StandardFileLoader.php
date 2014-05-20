<?php


namespace FlyFoundation\Core;

use FlyFoundation\Dependencies\AppConfig;

class StandardFileLoader implements FileLoader{

    use AppConfig;

    public function findFile($fileName, array $extensions = [])
    {
        $result = $this->findSpecialFile($fileName, $extensions);

        if($result){
            return $result;
        }elseif(is_file($fileName)){
            return $fileName;
        }else{
            return false;
        }
    }

    public function findTemplate($name)
    {
        return $this->findFile("templates/".$name.".mustache");
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
        $specialFilePattern = "/^(templates|pages)\\/(.*)$/";
        $matches = [];
        $isSpecialFile = preg_match($specialFilePattern, $fileName, $matches);

        if(!$isSpecialFile){
            return false;
        }

        switch($matches[1]){
            case "templates" :
                $paths = $this->getAppConfig()->templateDirectories->asArray();
                break;
            case "pages" :
                $paths = $this->getAppConfig()->pageDirectories->asArray();
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