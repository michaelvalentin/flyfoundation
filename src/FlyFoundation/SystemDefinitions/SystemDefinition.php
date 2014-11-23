<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class SystemDefinition extends DefinitionComponent{
    /**
     * @var EntityDefinition
     */
    private $entityDefinitions = [];

    public function setEntityDefinitions(array $entityDefinitions)
    {
        $this->requireOpen();
        $this->entityDefinitions = [];
        foreach($entityDefinitions as $entityDefinition){
            if(!$entityDefinition instanceof EntityDefinition){
                throw new InvalidArgumentException(
                    "The supplied array contains an entry, which is not an entity definition"
                );
            }
            $this->entityDefinitions[$entityDefinition->getName()] = $entityDefinition;
        }
    }

    public function getEntityDefinitions()
    {
        return $this->entityDefinitions;
    }

    public function getEntityDefinition($entityName)
    {
        if(isset($this->entityDefinitions[$entityName])){
            return $this->entityDefinitions[$entityName];
        }else{
            throw new InvalidArgumentException(
                "This system contains no entity called '$entityName'"
            );
        }
    }

    public function containsEntityDefinition($entityName)
    {
        if(isset($this->entityDefinitions[$entityName])){
            return true;
        }
        return false;
    }
} 