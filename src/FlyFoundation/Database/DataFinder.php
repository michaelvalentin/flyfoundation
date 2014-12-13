<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\Conditions\DataCondition;
use FlyFoundation\Models\Entity;
interface DataFinder
{
    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetch(array $conditions);

    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetchRaw(array $conditions);
}