<?php


namespace FlyFoundation\Database;

use FlyFoundation\SystemDefinitions\EntityDefinition;

interface DataMapper {

    public function __construct(EntityDefinition $entityDefinition);

    /**
     * @return FluentPDO
     */
    public function getPdo();

    /**
     * @param array $data
     *
     * @return void
     */
    public function save($data);

    /**
     * @param $id
     *
     * @return void
     */
    public function delete($id);

    /**
     * @param $id
     *
     * @return array
     */
    public function load($id);
} 