<?php


namespace FlyFoundation\Database;

use FlyFoundation\Models\PersistentEntity;
use FlyFoundation\SystemDefinitions\EntityDefinition;

interface DataMapper {

    public function __construct(EntityDefinition $entityDefinition);

    /**
     * @param PersistentEntity $persistentEntity
     *
     * @return void
     */
    public function save(PersistentEntity $persistentEntity);

    /**
     * @param $id
     *
     * @return void
     */
    public function delete($id);

    /**
     * @param mixed $primaryKey
     *
     * @return PersistentEntity
     */
    public function load($primaryKey);
} 