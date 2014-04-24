<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class SystemDefinition extends DefinitionComponent{
    /** @var EntityDefinition[] */
    private $entities;
    /** @var string */
    private $name;

    /**
     * @return EntityDefinition[]
     */
    public function getEntities()
    {
        $this->requireFinalized();
        return $this->entities;
    }

    /**
     * @param $name
     * @return EntityDefinition
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function getEntity($name)
    {
        $this->requireFinalized();
        if(!$this->hasEntity($name)){
            throw new InvalidArgumentException("No entity with the name ".$name." is found in the system definition");
        }
        return $this->entities[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasEntity($name)
    {
        $this->requireFinalized();
        return isset($this->entities[$name]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $this->requireFinalized();
        return $this->name;
    }

    protected function applyName($name)
    {
        $this->name = $name;
    }

    protected function applyEntities($entitiesData)
    {
        foreach($entitiesData as $entityData)
        {
            $entity = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\EntityDefinition");
            $entity->applyOptions($entityData);
            $entity->setEntityDefinition($this);
            if(!isset($entityData["name"])){
                throw new InvalidArgumentException("An entity must have a name to be in a system definition.");
            }
            $this->entities[$entityData["name"]] = $entity;
        }
    }
} 