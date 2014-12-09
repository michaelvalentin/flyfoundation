<?php


namespace TestApp\Database;


use FlyFoundation\Database\DataMapper;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\PersistentEntity;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class MyTestDataMapper implements DataMapper{

    /**
     * @param Entity $entity
     * @return int
     */
    public function save(Entity &$entity)
    {
        // TODO: Implement save() method.
    }

    /**
     * @param array $identifier
     * @return Entity
     */
    public function load(array $identifier)
    {
        // TODO: Implement load() method.
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function delete(Entity &$entity)
    {
        // TODO: Implement delete() method.
    }
}