<?php


namespace FlyFoundation\Database;

use FlyFoundation\Models\Model;
use FlyFoundation\SystemDefinitions\ClassDefinition;

interface DataMapper {

    public function __construct(ClassDefinition $classDefinition);

    /**
     * @param Model $model
     * @return Model
     */
    public function save(Model $model);

    /**
     * @param Model $model
     */
    public function delete(Model $model);

    public function deleteById($id);

    /**
     * @param $id
     * @return Model
     */
    public function load($id);
} 