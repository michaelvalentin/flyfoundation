<?php


namespace TestApp\Database;


use FlyFoundation\Database\DataMapper;
use FlyFoundation\Models\PersistentEntity;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class MySqlMyTestDataMapper implements DataMapper{

    /**
     * @param PersistentEntity $persistentEntity
     *
     * @return void
     */
    public function save(PersistentEntity $persistentEntity)
    {
        // TODO: Implement save() method.
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param integer $id
     * @return PersistentEntity
     */
    public function load($id)
    {
        // TODO: Implement load() method.
    }
}