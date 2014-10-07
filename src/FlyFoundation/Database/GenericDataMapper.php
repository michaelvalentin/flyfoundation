<?php

namespace FlyFoundation\Database;


use FlyFoundation\Factory;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Database\Table;

class GenericDataMapper implements DataMapper
{

    private $entityName;

    /**
     * @var Table
     */
    private $table;

    /**
     * @param Entity $entity
     * @return int
     */
    public function save(Entity $entity)
    {
        // TODO: Implement
    }

    /**
     * @param int $id
     * @return Entity
     */
    public function load($id)
    {
        $data = $this->table->readRow($id);
        return Factory::load($this->entityName, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        $this->table->deleteRow($id);
    }

    /**
     * @param Table $dataTable
     * @return void
     */
    public function setTable(Table $table)
    {
        $this->table = $table;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

} 