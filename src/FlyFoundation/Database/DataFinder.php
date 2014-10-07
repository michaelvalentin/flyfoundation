<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\Condition;
use FlyFoundation\Models\Entity;
interface DataFinder
{
    /**
     * @param Condition[] $conditions
     * @return Entity[]
     */
    public function fetch($conditions);

    /**
     * @param Condition[] $conditions
     * @return Entity[]
     */
    public function fetchRaw($conditions);
}