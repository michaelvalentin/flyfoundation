<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Database;


use FlyFoundation\Models\Model;
use FlyFoundation\SystemDefinitions\EntityDefinition;

class MySqlDataMapper implements DataMapper
{
    public function __construct(EntityDefinition $entityDefinition)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * @param Model $model
     *
     * @return Model
     */
    public function save(Model $model)
    {
        // TODO: Implement save() method.
    }

    /**
     * @param Model $model
     */
    public function delete(Model $model)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($id)
    {
        // TODO: Implement deleteById() method.
    }

    /**
     * @param $id
     *
     * @return Model
     */
    public function load($id)
    {
        // TODO: Implement load() method.
    }
}