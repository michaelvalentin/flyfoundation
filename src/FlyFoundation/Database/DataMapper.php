<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\DataStore;
use FlyFoundation\Models\Entity;

interface DataMapper
{
    /**
     * @param Entity $entity
     * @return int
     */
    public function save(Entity &$entity);

    /**
     * @param array $identifier
     * @return Entity
     */
    public function load(array $identifier);

    /**
     * @param Entity $entity
     * @return void
     */
    public function delete(Entity &$entity);
}