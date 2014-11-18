<?php


namespace FlyFoundation\Database;

use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Dependencies\MySqlDatabase;
use FlyFoundation\Exceptions\InvalidArgumentException;
use PDO;
use PDOException;

class MySqlGenericDataStore extends GenericDataStore{

    use MySqlDatabase;

    /**
     * @param array $data
     * @return int
     */
    public function createEntry(array $data)
    {
        $this->validateData($data);

        $columns = array_keys($data);
        $prefixedColumns = array_map(function($value){return ":".$value;},$columns);
        $storageData = $this->convertToStorageFormat($data);
        $bindData = array_combine($prefixedColumns,array_values($storageData));

        $insertQuery = 'INSERT INTO '.$this->getName().' ('.implode(",",$columns).') VALUES ('.implode(",",$prefixedColumns).')';

        $preapredInsertStatement = $this->getMySqlDatabase()->prepare($insertQuery);

        try{
            $preapredInsertStatement->execute($bindData);
            return $this->getMySqlDatabase()->lastInsertId();
        } catch(PDOException $e){
            // TODO: Handle exceptions
            throw $e;
        }

    }

    /**
     * @param array $identity
     * @return array
     */
    public function readEntry(array $identity)
    {
        $this->validateIdentity($identity);
        $columns = array_keys($identity);
        $prefixedColumns = array_map(function($value){return ":".$value;},$columns);
        $bindData = array_combine($prefixedColumns,array_values($identity));

        $conditions = [];
        foreach(array_combine($columns, $prefixedColumns) as $column => $prefixedColumn){
            $conditions[] = '`'.$column.'` = '.$prefixedColumn;
        }

        $selectQuery = 'SELECT * FROM '.$this->getName().' WHERE '.implode(" AND ",$conditions)." LIMIT 1";
        $preparedSelectStatement = $this->getMySqlDatabase()->prepare($selectQuery);
        if(!$preparedSelectStatement->execute($bindData)){
            throw new InvalidArgumentException("No entry with the identity (".implode(",",$identity).") could be found in the DataStore: ".$this->getName());
        }

        $resultData = $preparedSelectStatement->fetch(PDO::FETCH_ASSOC);
        return $this->convertFromStorageFormat($resultData);
    }

    /**
     * @param array $data
     * @return void
     */
    public function updateEntry(array $data)
    {
        $this->validateData($data);
        $identity = $this->extractIdentity($data);
        $this->validateIdentity($identity);

        $columns = array_keys($data);
        $prefixedColumns = array_map(function($value){return ":".$value;},$columns);
        $bindData = array_combine($prefixedColumns,array_values($data));

        $updateConditions = [];
        $fieldNamesWithoutIdentity = array_diff(array_keys($data),array_keys($identity));
        foreach($fieldNamesWithoutIdentity as $fieldName){
            $updateConditions[] = "`".$fieldName."` = :".$fieldName;
        }

        $identityConditions = [];
        foreach(array_keys($identity) as $fieldName){
            $identityConditions[] = "`".$fieldName."` = :".$fieldName;
        }

        $updateQuery = 'UPDATE '.$this->getName().' SET '.implode(",",$updateConditions).' WHERE '.implode(" AND ",$identityConditions);
        $preparedUpdateStatement = $this->getMySqlDatabase()->prepare($updateQuery);
        if(!$preparedUpdateStatement->execute($bindData)){
            throw new InvalidArgumentException("An error occured. It might be that no entry with the identity (".implode(",",$identity).") could be found in the DataStore: ".$this->getName());
        }
    }

    /**
     * @param array $id
     * @return void
     */
    public function deleteEntry(array $identity)
    {
        $this->validateIdentity($identity);
        $columns = array_keys($identity);
        $prefixedColumns = array_map(function($value){return ":".$value;},$columns);
        $bindData = array_combine($prefixedColumns,array_values($identity));

        $conditions = [];
        foreach(array_combine($columns, $prefixedColumns) as $column => $prefixedColumn){
            $conditions[] = '`'.$column.'` = '.$prefixedColumn;
        }

        $deleteQuery = 'DELETE FROM '.$this->getName().' WHERE '.implode(" AND ",$conditions)." LIMIT 1";
        $preparedDeleteStatement = $this->getMySqlDatabase()->prepare($deleteQuery);
        if(!$preparedDeleteStatement->execute($bindData)){
            throw new InvalidArgumentException("No entry with the identity (".implode(",",$identity).") could be found in the DataStore: ".$this->getName());
        }
    }
}