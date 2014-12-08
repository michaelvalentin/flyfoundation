<?php

namespace FlyFoundation\Database;


use FlyFoundation\Core\Generic;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Factory;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Database\DataStore;
use FlyFoundation\Util\Map;

class GenericDataMapper implements DataMapper, Generic
{

    private $entityName;

    /**
     * @var DataStore
     */
    private $dataStore;

    /**
     * @var Map
     */
    private $entityToStorageNameMapping;

    /**
     * @var Map
     */
    private $storageToEntityNameMapping;

    public function __construct(){
        $this->entityToStorageNameMapping = new Map();
        $this->storageToEntityNameMapping = new Map();
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function save(Entity &$entity)
    {
        $entityData = $entity->getPersistentData("This is called from the data mapper");
        $storageData = $this->getDataForStorage($entityData);
        $identity = $this->dataStore->extractIdentity($storageData);
        $valid = $this->dataStore->isValidData($storageData);
        $validId = $this->dataStore->isValidIdentity($identity);

        if($valid && !$validId){
            $lastInsertId = (int) $this->dataStore->createEntry($storageData);
            $validInsertId = is_int($lastInsertId) && $lastInsertId > 0;
            $singleColumnIdentifier = count($identity) == 1;
            $firstIdentifierName = array_keys($identity)[0];
            $identifierEntityFieldName = $this->getEntityFieldName($firstIdentifierName);

            if($validInsertId && $singleColumnIdentifier){
                $entityData[$identifierEntityFieldName] = $lastInsertId;
                $entity->setPersistentData($entityData, "This is called from the data mapper");
            }
        }elseif($valid && $this->dataStore->containsEntry($identity)){
            $this->dataStore->updateEntry($storageData);
        }elseif($valid){
            $this->dataStore->createEntry($storageData);
        }else{
            throw new InvalidArgumentException(
                "The provided entity is not valid for saving to persistent storage, according to the storage directives."
            );
        }
    }

    /**
     * @param array $identifier
     * @return Entity
     */
    public function load(array $identifier)
    {
        $identifier = $this->getDataForStorage($identifier);
        $storageData = $this->dataStore->readEntry($identifier);
        $entityData = $this->getDataForEntity($storageData);
        $result = Factory::load($this->entityName);
        $result->setPersistentData($entityData, "This is called from the data mapper");
        return $result;
    }

    /**
     * @param \FlyFoundation\Models\Entity $entity
     * @internal param array $identifier
     * @return void
     */
    public function delete(Entity &$entity)
    {
        $entityData = $entity->getPersistentData("This is called from the data mapper");
        $storageData = $this->getDataForStorage($entityData);
        $identity = $this->dataStore->extractIdentity($storageData);
        $this->dataStore->deleteEntry($identity);
        $entity->setPersistentData([],"This is called from the data mapper");
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

    public function addNameMapping($entityFieldName, $storageFieldName)
    {
        $this->entityToStorageNameMapping->put($entityFieldName, $storageFieldName);
        $this->storageToEntityNameMapping->put($storageFieldName, $entityFieldName);
    }

    protected function getStorageFieldName($entityFieldName)
    {
        if($this->entityToStorageNameMapping->containsKey($entityFieldName)){
            return $this->entityToStorageNameMapping->get($entityFieldName);
        }
        return $entityFieldName;
    }

    protected function getEntityFieldName($storageFieldName)
    {
        if($this->storageToEntityNameMapping->containsKey($storageFieldName)){
            return $this->storageToEntityNameMapping->get($storageFieldName);
        }
        return $storageFieldName;
    }

    protected function getDataForStorage(array $entityData)
    {
        $result = [];
        foreach($entityData as $entityFieldName => $value){
            $storageFieldName = $this->getStorageFieldName($entityFieldName);
            $result[$storageFieldName] = $value;
        }
        return $result;
    }

    protected function getDataForEntity(array $storageData)
    {
        $result = [];
        foreach($storageData as $storageFieldName => $value){
            $entityFieldName = $this->getEntityFieldName($storageFieldName);
            $result[$entityFieldName] = $value;
        }
        return $result;
    }

    /**
     * @return void
     */
    public function afterConfiguration()
    {
        // TODO: Implement afterConfiguration() method.
    }
}