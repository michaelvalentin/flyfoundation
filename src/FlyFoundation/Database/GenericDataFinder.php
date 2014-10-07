<?php

namespace FlyFoundation\Database;


use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Models\Entity;
use FlyFoundation\Database\Condition;
use PDO;
use PDOException;

class GenericDataFinder implements DataFinder
{
    use AppConfig;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var Condition[]
     */
    private $defaultConditions = array();

    /**
     * @var Condition[]
     */
    private $allConditions = array();

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param Condition[] $conditions
     * @return Entity[]
     */
    public function fetch($conditions)
    {
        $query = 'SELECT * FROM `'.$this->tableName.'` WHERE ';

        $this->allConditions = array_merge($this->defaultConditions, $conditions);

        $values = array();
        foreach($this->allConditions as $i => $condition){

            $query .= $condition->getString();
            $values = array_merge($values, $condition->getValues());

            if($i+1 != count($this->allConditions)) $query .= ' AND ';
        }

        $pdo = $this->getPDO();
        $stmt = $pdo->prepare($query);
        $stmt->execute($values);
        $entities = $stmt->fetchAll(PDO::FETCH_CLASS, $this->entityName);
        return $entities;
    }

    /**
     * @param Condition[] $conditions
     * @return Entity[]
     */
    public function fetchRaw($conditions)
    {
        $query = 'SELECT * FROM `'.$this->tableName.'` WHERE ';

        $values = array();
        foreach($conditions as $i => $condition){

            $query .= $condition->getString();
            $values = array_merge($values, $condition->getValues());

            if($i+1 != count($conditions)) $query .= ' AND ';
        }

        $pdo = $this->getPDO();
        $stmt = $pdo->prepare($query);
        $stmt->execute($values);
        $entities = $stmt->fetchAll(PDO::FETCH_CLASS, $this->entityName);
        return $entities;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * @param string $tableName
     */
    public function setTable($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param Condition $condition
     */
    public function addDefaultCondition(Condition $condition)
    {
        $this->defaultConditions[] = $condition;
    }

    private function getPDO()
    {
        if($this->pdo instanceof PDO) return $htis->pdo;
        else {
            $config = $this->getAppConfig();

            $dbHost = $config->get('db_host', 'localhost');
            $dbUser = $config->get('db_user');
            $dbPass = $config->get('db_pass');
            $dbName = $config->get('db_name');

            try{
                $this->pdo = new PDO('mysql:dbname='.$dbName.';host='.$dbHost,$dbUser,$dbPass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                return $this->pdo;
            } catch(PDOException $e){
                // TODO: Exception handling
            }
        }
    }
}