<?php


namespace TestApp\Database;


use FlyFoundation\Database\GenericDataMapper;

class DemoEntityDataMapper extends GenericDataMapper{
    public function getDataStore()
    {
        return $this->dataStore;
    }

    public function getEntityToStorageMapping()
    {
        return $this->entityToStorageNameMapping;
    }

    public function getStorageToEntityMapping()
    {
        return $this->storageToEntityNameMapping;
    }
} 