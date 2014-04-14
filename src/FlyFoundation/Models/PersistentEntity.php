<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Models;

use FlyFoundation\SystemDefinitions\EntityDefinition;

abstract class PersistentEntity implements Entity, Model
{
    protected  $columnValuePairs;
    protected $entityDefinition;

    public function __construct(EntityDefinition $entityDefinition, array $columnValuePairs)
    {
        $this->entityDefinition = $entityDefinition;
        $this->columnValuePairs = $columnValuePairs;
    }

    public function getDefinition()
    {
        return $this->entityDefinition;
    }

    public function getPersistentData()
    {
        $persistentData = [];
        $data = $this->columnValuePairs;

        foreach($this->entityDefinition->getFields() as $field){
            $columnName = $field->getColumnName();
            if(isset($data[$columnName])){
                $persistentData[$columnName] = $data[$columnName];
            }
        }
        return $persistentData;
    }

    public function getPrimaryKey()
    {
        $primaryKey = [];
        $primaryColumns = $this->entityDefinition->getPrimaryColumns();

        foreach($this->columnValuePairs as $column => $value){
            if(in_array($column, $primaryColumns)){
                $primaryKey[$column] = $value;
            }
        }

        if(count($primaryKey) === count($primaryColumns)) return $primaryKey;
        return false;
    }

}