<?php

namespace FlyFoundation\Models;

use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\EntityField;

class PersistentEntity implements Entity, Model
{
    protected  $columnValuePairs;
    protected $entityDefinition;

    public function __construct(EntityDefinition $entityDefinition, array $data = array())
    {
        $this->entityDefinition = $entityDefinition;
        $this->columnValuePairs = $data;
    }

    public function getDefinition()
    {
        return $this->entityDefinition;
    }

    public function getPersistentData()
    {
        $persistentFields = $this->getDefinition()->getPersistentFields();
        return $this->fieldsDataAsArray($persistentFields);
    }

    public function getPrimaryKeyValues()
    {
        $primaryKeyFields = $this->entityDefinition->getPrimaryKeyFields();
        $result = $this->fieldsDataAsArray($primaryKeyFields);
        if(count($primaryKeyFields) != count($result)){
            return false;
        }
        return $result;
    }


    private function fieldsDataAsArray(array $entityFields)
    {
        $returnData = [];
        $data = $this->columnValuePairs;

        foreach($entityFields as $field){
            if(!$field instanceof EntityField){
                throw new InvalidArgumentException("The supplied array must only contain entries of type EntityField");
            }
            $columnName = $field->getname();
            if(isset($data[$columnName])){
                $returnData[$columnName] = $data[$columnName];
            }
        }
        return $returnData;
    }

    /**
     * @return array
     */
    public function asArray()
    {
        // TODO: Implement asArray() method.
    }

    public function fromArray(array $data)
    {
        // TODO: Implement fromArray() method.
    }
}