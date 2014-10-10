<?php

namespace FlyFoundation\Database;


use FlyFoundation\Factory;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Database\DataStore;

class GenericDataMapper implements DataMapper
{

    private $entityName;

    /**
     * @var DataStore
     */
    private $dataStore;

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
        $data = $this->dataStore->readRow($id);
        return Factory::load($this->entityName, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        $this->dataStore->deleteRow($id);
    }

    /**
     * @param DataStore $dataTable
     * @return void
     */
    public function setDataStore(DataStore $table)
    {
        $this->dataStore = $table;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

} 