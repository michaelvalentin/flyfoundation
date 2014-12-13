<?php

namespace FlyFoundation\Database;


use FlyFoundation\Core\Generic;
use FlyFoundation\Database\Conditions\DataCondition;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Exceptions\NotImplementedException;
use FlyFoundation\Models\Entity;

class GenericDataFinder extends GenericDataHandler implements DataFinder, Generic
{

    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetch(array $conditions)
    {
        // TODO: Implement fetch() method.
    }

    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetchRaw(array $conditions)
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