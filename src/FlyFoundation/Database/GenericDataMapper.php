<?php

namespace FlyFoundation\Database;


use FlyFoundation\Core\Generic;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Factory;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\EntityFields\EntityField;
use FlyFoundation\Database\DataStore;
use FlyFoundation\Models\GenericEntity;
use FlyFoundation\Util\Map;

class GenericDataMapper extends GenericDataHandler implements DataMapper, Generic
{

    /**
     * @param Entity $entity
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
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
        /** @var Entity $result */
        $result = Factory::loadModel($this->entityName);
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
     * @return void
     */
    public function afterConfiguration()
    {
        // TODO: Implement afterConfiguration() method.
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }
}