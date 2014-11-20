<?php

namespace FlyFoundation\Database;


use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
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
        $data = $entity->getPersistentData("This is called from the data mapper");
        $identity = $this->dataStore->extractIdentity($data);
        $valid = $this->dataStore->validateData($data);
        $validId = $this->dataStore->validateIdentity($identity);
        $result = false;

        if($valid && !$validId){
            $this->dataStore->createEntry($data);
        }elseif($valid && $this->dataStore->containsEntry($identity)){
            $this->dataStore->updateEntry($data);
        }elseif($valid){
            $this->dataStore->createEntry($data);
        }else{
            throw new InvalidArgumentException(
                "The provided entity is not valid for saving to persistent storage, according to the storage directives."
            );
        }

        return $result;
    }

    /**
     * @param array $identifier
     * @return Entity
     */
    public function load(array $identifier)
    {
        $data = $this->dataStore->readEntry($identifier);
        $result = Factory::load($this->entityName);
        $result->setPersistentData($data, "This is called from the data mapper");
        return $result;
    }

    /**
     * @param array $identifier
     * @return void
     */
    public function delete(array $identifier)
    {
        $this->dataStore->deleteEntry($identifier);
    }

    /**
     * @param DataStore $dataStore
     * @return void
     */
    public function setDataStore(DataStore $dataStore)
    {
        $this->dataStore = $dataStore;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

} 