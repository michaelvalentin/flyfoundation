<?php


namespace FlyFoundation\LsdParser;


use FlyFoundation\Exceptions\InvalidArgumentException;

class DirectiveTreeNode {
    /** @var  int */
    private $type;
    /** @var  string */
    private $label;
    /** @var  string */
    private $value;
    /** @var  string */
    private $origin;
    /** @var  DirectiveTreeNode[] */
    private $childNodes;

    /**
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->validateInput($properties);
        $this->setProperties($properties);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param DirectiveTreeNode[] $childNodes
     */
    public function setChildNodes(array $childNodes)
    {
        $this->validateChildNodes($childNodes);
        foreach($childNodes as $childNode){
            $this->addChildNode($childNode);
        }
    }

    public function addChildNode(DirectiveTreeNode $child)
    {
        $this->childNodes[] =& $child;
    }

    /**
     * @return DirectiveTreeNode[]
     */
    public function getChildNodes()
    {
        return $this->childNodes;
    }

    private function validateInput($properties)
    {
        $this->validateType($properties);
        $this->validateLabel($properties);
        $this->validateValue($properties);
        $this->validateOrigin($properties);
        $this->validateChildNodes($properties);
    }

    private function setProperties($properties)
    {
        $fields = [
            "type",
            "label",
            "value",
            "origin",
            "childNodes"
        ];

        if(!isset($properties["childNodes"])){
            $properties["childNodes"] = [];
        }

        foreach($fields as $field)
        {
            if(isset($properties[$field])){
                $this->$field = $properties[$field];
            }
        }

    }

    private function validateType($properties)
    {
        if(!isset($properties["type"])){
            throw new InvalidArgumentException(
                "No type was set for the directive tree node"
            );
        }

        if(!DirectiveType::isValidType($properties["type"])){
            throw new InvalidArgumentException(
                "The type ".$properties["type"]." is not a valid directive type,
                please use eg. DirectiveType::Entity to get the correct type"
            );
        }
    }

    private function validateLabel($properties)
    {
        if(!isset($properties["label"])){
            throw new InvalidArgumentException(
                "No label was set for the directive tree node"
            );
        }

        if(!is_string($properties["label"])){
            throw new InvalidArgumentException(
                "The label of a directive tree node must be a string"
            );
        }
    }

    private function validateValue($properties)
    {
        if(isset($properties["value"]) && !is_string($properties["value"]))
        {
            throw new InvalidArgumentException(
                "The value of a directive tree node must be a string"
            );
        }
    }

    private function validateOrigin($properties)
    {
        if(isset($properties["origin"]) && !is_string($properties["origin"]))
        {
            throw new InvalidArgumentException(
                "The origin of a directive tree node must be a string"
            );
        }
    }

    private function validateChildNodes($properties)
    {
        if(!isset($properties["childNodes"])){
            return;
        }

        if(!is_array($properties["childNodes"])){
            throw new InvalidArgumentException(
                "Child nodes supplied for directive tree node must be an array"
            );
        }

        foreach($properties["childNodes"] as $childNode){
            if(!($childNode instanceof DirectiveTreeNode)){
                throw new InvalidArgumentException(
                    "All elements of child node array, must be of type directive tree node, in a directive tree node"
                );
            }
        }
    }
} 