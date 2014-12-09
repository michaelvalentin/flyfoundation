<?php


namespace TestApp\Database;


use FlyFoundation\Database\DataCondition;
use FlyFoundation\Database\DataFinder;
use FlyFoundation\Database\Entity;
use FlyFoundation\Database\QueryCondition;
use FlyFoundation\Database\QuerySorting;
use FlyFoundation\Database\QueryType;
use FlyFoundation\Models\PersistentEntity;

class MyOtherTestDataFinder implements DataFinder{

    /**
     * @param DataCondition[] $conditions
     * @return \FlyFoundation\Models\Entity[]
     */
    public function fetch($conditions)
    {
        // TODO: Implement fetch() method.
    }

    /**
     * @param DataCondition[] $conditions
     * @return \FlyFoundation\Models\Entity[]
     */
    public function fetchRaw($conditions)
    {
        // TODO: Implement fetchRaw() method.
    }
}