<?php


namespace FlyFoundation\Database;

use FlyFoundation\Database\Conditions\DataCondition;
use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Core\Dependant;
use FlyFoundation\Dependencies\MySqlDatabase;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Exceptions\NotImplementedException;
use PDO;
use PDOException;

class MySqlGenericDataStore extends GenericDataStore implements Dependant{

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

        $insertQuery = 'INSERT INTO '.$this->getStorageName().' ('.implode(",",$columns).') VALUES ('.implode(",",$prefixedColumns).')';

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

        $selectQuery = 'SELECT * FROM '.$this->getStorageName().' WHERE '.implode(" AND ",$conditions)." LIMIT 1";
        $preparedSelectStatement = $this->getMySqlDatabase()->prepare($selectQuery);
        $preparedSelectStatement->execute($bindData);
        $resultData = $preparedSelectStatement->fetch(PDO::FETCH_ASSOC);

        if(!is_array($resultData)){
            throw new InvalidArgumentException("No entry with the identity (".implode(",",$identity).") could be found in the DataStore: ".$this->getEntityName());
        }

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
        if(!$this->containsEntry($identity)){
            throw new InvalidArgumentException(
                "No entries with the id: (".implode(", ",$identity).") exists, and hence can not be updated"
            );
        }

        $columns = array_keys($data);
        $prefixedColumns = array_map(function($value){return ":".$value;},$columns);
        $storageData = $this->convertToStorageFormat($data);
        $bindData = array_combine($prefixedColumns,array_values($storageData));

        $updateConditions = [];
        $fieldNamesWithoutIdentity = array_diff(array_keys($data),array_keys($identity));
        foreach($fieldNamesWithoutIdentity as $fieldName){
            $updateConditions[] = "`".$fieldName."` = :".$fieldName;
        }

        $identityConditions = [];
        foreach(array_keys($identity) as $fieldName){
            $identityConditions[] = "`".$fieldName."` = :".$fieldName;
        }

        $updateQuery = 'UPDATE '.$this->getStorageName().' SET '.implode(",",$updateConditions).' WHERE '.implode(" AND ",$identityConditions)." LIMIT 1;";
        $preparedUpdateStatement = $this->getMySqlDatabase()->prepare($updateQuery);
        $preparedUpdateStatement->execute($bindData);
    }

    /**
     * @param array $id
     * @return void
     */
    public function deleteEntry(array $identity)
    {
        $this->validateIdentity($identity);
        if(!$this->containsEntry($identity)){
            throw new InvalidArgumentException(
                "No entries with the id: (".implode(", ",$identity).") exists, and hence can not be deleted"
            );
        }
        $columns = array_keys($identity);
        $prefixedColumns = array_map(function($value){return ":".$value;},$columns);
        $bindData = array_combine($prefixedColumns,array_values($identity));

        $conditions = [];
        foreach(array_combine($columns, $prefixedColumns) as $column => $prefixedColumn){
            $conditions[] = '`'.$column.'` = '.$prefixedColumn;
        }

        $deleteQuery = 'DELETE FROM '.$this->getStorageName().' WHERE '.implode(" AND ",$conditions)." LIMIT 1";
        $preparedDeleteStatement = $this->getMySqlDatabase()->prepare($deleteQuery);
        $preparedDeleteStatement->execute($bindData);
    }

    /**
     * @param array $identity
     * @return bool
     */
    public function containsEntry(array $identity)
    {
        $this->validateIdentity($identity);
        $columns = array_keys($identity);
        $prefixedColumns = array_map(function($value){return ":".$value;},$columns);
        $bindData = array_combine($prefixedColumns,array_values($identity));

        $conditions = [];
        foreach(array_combine($columns, $prefixedColumns) as $column => $prefixedColumn){
            $conditions[] = '`'.$column.'` = '.$prefixedColumn;
        }

        $existsQuery = 'SELECT COUNT(*) FROM '.$this->getStorageName().' WHERE '.implode(" AND ",$conditions)." LIMIT 1";
        $preparedExistsStatement = $this->getMySqlDatabase()->prepare($existsQuery);
        $preparedExistsStatement->execute($bindData);
        $result = $preparedExistsStatement->fetch();
        return $result[0] === "1";
    }

    /**
     * @param DataCondition[] $conditions
     */
    public function fetchEntries(array $conditions = [])
    {
        if(count($conditions)){
            throw new NotImplementedException(
                "The MySql data store does not yet support conditions in fetch statements"
            );
        }

        $fetchQuery = 'SELECT * FROM '.$this->getStorageName();
        $preparedFetchStatement = $this->getMySqlDatabase()->prepare($fetchQuery);
        $preparedFetchStatement->execute();
        return $preparedFetchStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return void
     */
    public function afterConfiguration()
    {
        // TODO: Implement afterConfiguration() method.
    }

    /**
     * @return void
     */
    public function onDependenciesLoaded()
    {
        // TODO: Implement onDependenciesLoaded() method.
    }
}