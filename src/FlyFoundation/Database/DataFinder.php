<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\DataCondition;
use FlyFoundation\Models\Entity;
interface DataFinder
{
    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetch($conditions);

    /**
     * @param DataCondition[] $conditions
     * @return Entity[]
     */
    public function fetchRaw($conditions);
}