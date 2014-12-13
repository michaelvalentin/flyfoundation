<?php


namespace FlyFoundation\Database;


use FlyFoundation\Util\Map;

abstract class GenericDataHandler {
    protected $entityName;

    /**
     * @var DataStore
     */
    protected $dataStore;

    /**
     * @var Map
     */
    protected $entityToStorageNameMapping;

    /**
     * @var Map
     */
    protected $storageToEntityNameMapping;

    public function __construct(){
        $this->entityToStorageNameMapping = new Map();
        $this->storageToEntityNameMapping = new Map();
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
            if(ctype_digit($value)){
                $value = intval($value);
            }elseif(!is_int($value) && is_numeric($value)){
                $value = floatval($value);
            }
            $result[$entityFieldName] = $value;
        }
        return $result;
    }
} 