<?php


namespace FlyFoundation\Database;

use FlyFoundation\Models\PersistentEntity;

interface DataMapper {

    /**
     * @param PersistentEntity $persistentEntity
     *
     * @return void
     */
    public function save(PersistentEntity $persistentEntity);

    /**
     * @param $id
     * @return void
     */
    public function delete($id);

    /**
     * @param integer $id
     * @return PersistentEntity
     */
    public function load($id);
} 