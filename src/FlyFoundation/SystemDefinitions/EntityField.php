<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\OpenPersistentEntity;

abstract class EntityField extends DefinitionComponent{

    private $entityDefinition;
    private $name;
    private $type;

    /**
     * @return EntityDefinition|null
     */
    public function getEntityDefinition()
    {
        return $this->entityDefinition;
    }

    public function setEntityDefinition(EntityDefinition $definition)
    {
        $this->entityDefinition = $definition;
    }

    public function getName()
    {
        $this->requireFinalized();
        return $this->name;
    }

    protected function applyName($name)
    {
        if(!is_string($name)){
            throw new InvalidOperationException("The name af the entity field should be a string");
        }
        $this->name = $name;
    }

    public function getType()
    {
        $this->requireFinalized();
        return $this->type;
    }

    public function applyType($typeName)
    {
        $this->type = $typeName;
    }
    public function validate()
    {
        if($this->name == null){
            throw new InvalidArgumentException("Every field must have a name.");
        }
        if($this->type == null){
            throw new InvalidArgumentException("Every field must have a type.");
        }
        $this->checkType();
        parent::validate();
    }

    private function checkType()
    {
        $simpleTypeNames = [
            "string",
            "text",
            "integer",
            "float",
            "boolean",
            "DateTime",
            "Date",
            "Time"
        ];
        $isSimpleType = in_array($this->type,$simpleTypeNames);
        $isEntity = $this->getEntityDefinition()->getSystemDefinition()->hasEntity($this->type);
        if(!$isSimpleType && !$isEntity){
            throw new InvalidArgumentException("The type '".$this->type."' is not a valid field type");
        }
    }


} 