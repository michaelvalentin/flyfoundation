<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Models\Entity;

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
        $allowedTypeNames = [
            "string",
            "text",
            "integer",
            "float",
            "boolean",
            "DateTime",
            "Date",
            "Time"
        ];
        if(in_array($typeName,$allowedTypeNames) || $this->isInstanceOfEntity($typeName)){
            $this->type = $typeName;
        }else{
            throw new InvalidArgumentException("The type '".$typeName."' is not a valid EntityField type");
        }
    }

    private function isInstanceOfEntity($typeName)
    {
        $model = $this->getFactory()->loadModel($typeName);
        if($model instanceof Entity){
            return true;
        }else{
            return false;
        }
    }

    public function finalize()
    {
        if($this->name == null){
            throw new InvalidArgumentException("Every field must have a name.");
        }
        if($this->type == null){
            throw new InvalidArgumentException("Every field must have a type.");
        }
        parent::finalize();
    }


} 