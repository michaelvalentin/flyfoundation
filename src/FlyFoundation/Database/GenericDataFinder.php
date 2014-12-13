<?php

namespace FlyFoundation\Database;


use FlyFoundation\Core\Generic;
use FlyFoundation\Database\Conditions\DataCondition;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\NotImplementedException;
use FlyFoundation\Factory;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\GenericEntity;

class GenericDataFinder extends GenericDataHandler implements DataFinder, Generic
{

    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetch(array $conditions = [])
    {
        $databaseEntries = $this->dataStore->fetchEntries([]);
        $result = [];
        foreach($databaseEntries as $entry){
            $entityData = $this->getDataForEntity($entry);
            /** @var GenericEntity $entity */
            $entity = Factory::loadModel($this->entityName);
            $entity->setPersistentData($entityData,"This is called from the data mapper");
            $result[] = $entity;
        }
        return $result;
    }

    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetchRaw(array $conditions = [])
    {
        return $this->fetch($conditions);
    }

    public function addDefaultCondition(DataCondition $condition)
    {
        throw new NotImplementedException(
            "Default conditions has not yet been implemented"
        );
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @return void
     */
    public function afterConfiguration()
    {
        // TODO: Implement afterConfiguration() method.
    }
}