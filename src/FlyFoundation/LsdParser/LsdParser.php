<?php


namespace FlyFoundation\LsdParser;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\SystemDefinitions\SystemDefinition;

class LsdParser {
    private $files = [];

    public function addFile($filePath)
    {
        if(!file_exists($filePath)){
            throw new InvalidArgumentException(
                "The supplied file '$filePath' does not seem to exist"
            );
        }
        $files[] = $filePath;
    }

    /**
     * @return SystemDefinition
     */
    public function getSystemDefinition()
    {
        $directiveReader = new DirectiveReader();
        foreach($this->files as $file)
        {
            $directiveReader->addFile($file);
        }

        $directiveTree = $directiveReader->getDirectiveTree();

        $directiveInterpreter = new DirectiveInterpreter();

        return $directiveInterpreter->getSystemDefinition($directiveTree);
    }
} 