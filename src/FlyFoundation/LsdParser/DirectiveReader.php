<?php


namespace FlyFoundation\LsdParser;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Util\ArrayHelper;

class DirectiveReader {

    private $files = [];

    /**
     * @param string $filePath
     */
    public function addFile($filePath)
    {
        if(!file_exists($filePath)){
            throw new InvalidArgumentException(
                "The supplied file '$filePath' does not seem to exist"
            );
        }
        $this->files[] = $filePath;
    }

    /**
     * @return DirectiveTreeNode
     */
    public function getDirectiveTree()
    {
        $directiveLines = $this->readFiles();
        $directiveLineArrays = $this->parseDirectiveLines($directiveLines);
        return $this->buildTree($directiveLineArrays);
    }

    private function readFiles()
    {
        $allLines = [];
        foreach($this->files as $file)
        {
            $linesInFile = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $attributedLines = $this->attributeLines($linesInFile, $file);
            $allLines = array_merge($allLines, $attributedLines);
        }
        return $allLines;
    }

    private function parseDirectiveLines($directiveLines)
    {
        $directiveArrays = [];
        foreach($directiveLines as $directiveLine)
        {
            $directiveArrays[] = $this->parseDirectiveLine($directiveLine);
        }
        return $directiveArrays;
    }

    private function parseDirectiveLine($directiveLine)
    {
        $line = $directiveLine["content"];
        $lineNumber = $directiveLine["lineNumber"];
        $fileName = $directiveLine["fileName"];
        $origin = "File: '$fileName', Line number: $lineNumber";

        if(preg_match("/\t/",$line)){
            throw new InvalidArgumentException(
                "Tab indentation were found in ($origin). LSD files should not
                contain tabs, as it breaks the visual indentation style"
            );
        }

        $indentation = strspn($line, " ");

        $matches = [];
        $success = preg_match(
            "/^\\s*(?<type>[\\!\\?\\&\\*\\<\\>\\#\\$\\~\\%\\@\\+]*)\\s*(?<label>[A-Za-z][A-Za-z0-9]*)\\s*(:\\s*(?<value>.+))?$/",
            $line,
            $matches
        );

        if(!$success){
            throw new InvalidArgumentException(
                "The directive '$line' can not be parsed as a valid LSD directive ($origin)"
            );
        }

        $type = isset($matches["type"]) ? DirectiveType::FromSymbol($matches["type"]) : null;
        $label = isset($matches["label"]) ? $matches["label"] : null;
        $value = isset($matches["value"]) ? $matches["value"] : null;

        if(!$type){
            throw new InvalidArgumentException(
                "The type of the directive '$line' could not be determined ($origin)"
            );
        }

        $result = [
            "type" => $type,
            "label" => $label,
            "value" => $value,
            "origin" => $origin,
            "indent" => $indentation
        ];

        return $result;
    }

    private function attributeLines($linesInFile, $fileName)
    {
        $i = 1;
        $output = [];

        foreach($linesInFile as $line)
        {
            $output[] = [
                "content" => $line,
                "lineNumber" => $i,
                "fileName" => $fileName
            ];
            $i++;
        }

        return $output;
    }

    private function buildTree($directiveLineArrays)
    {
        $rootLine = [
            "type" => DirectiveType::System,
            "label" => "System",
            "value" => null,
            "origin" => "System definition tree root",
            "indent" => -1
        ];
        array_unshift($directiveLineArrays, $rootLine);
        $directiveTree = ArrayHelper::IndentedLinesToTreeArray($directiveLineArrays)[0];

        return $this->directiveTreeFromDirectiveArrayTree($directiveTree);
    }

    private function directiveTreeFromDirectiveArrayTree($directiveTree)
    {
        if(!isset($directiveTree["children"]) || !$directiveTree["children"] || count($directiveTree["children"]) == 0){
            return new DirectiveTreeNode($directiveTree);
        }else{
            $node = new DirectiveTreeNode($directiveTree);
            foreach($directiveTree["children"] as $childDirectiveTree)
            {
                $node->addChildNode($this->directiveTreeFromDirectiveArrayTree($childDirectiveTree));
            }
            return $node;
        }
    }
} 