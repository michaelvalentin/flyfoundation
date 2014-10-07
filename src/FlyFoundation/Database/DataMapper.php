<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\Table;
use FlyFoundation\Models\Entity;

interface DataMapper
{
    /**
     * @param Entity $entity
     * @return int
     */
    public function save(Entity $entity);

    /**
     * @param int $id
     * @return Entity
     */
    public function load($id);

    /**
     * @param int $id
     * @return void
     */
    public function delete($id);
}