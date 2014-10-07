<?php

namespace FlyFoundation\Database;

use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Database\Field;
use PDO;
use PDOException;
use FlyFoundation\Exceptions\InvalidArgumentException;

class GenericTable implements Table
{
    use AppConfig;

    /**
     * @var string
     */
    private $table;

    /**
     * @var Field[]
     */
    private $fields = array();

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param string $tableName
     * @throws \FlyFoundation\Exceptions\UnsetDependencyException
     * @throws PDOException
     */
    public function __construct($tableName)
    {
        $this->table = $tableName;

        $config = $this->getAppConfig();

        $dbHost = $config->get('db_host', 'localhost');
        $dbUser = $config->get('db_user');
        $dbPass = $config->get('db_pass');
        $dbName = $config->get('db_name');

        try{
            $this->pdo = new PDO('mysql:dbname='.$dbName.';host='.$dbHost,$dbUser,$dbPass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch(PDOException $e){
            // TODO: Exception handling
        }

    }


    /**
     * @param array $data
     * @return int
     */
    public function createRow(array $data)
    {
        $query = 'INSERT INTO '.$this->table.' SET';

        $fieldCount = count($this->fields);
        $values = array();
        foreach($this->fields as $i => $field){

            $fieldName = $field->getName();

            $query .= $fieldName.'=:'.$fieldName;

            if($field->isRequired() && empty($data[$fieldName])){
                throw new InvalidArgumentException('Table field "'.$field->getName().'" is required, but received no data.');
            }

            if(empty($data[$fieldName]) && $field->getDefaultValue()){
                $value = $field->getDefaultValue();
            } elseif($field->isAutoIncrement() || empty($data[$fieldName])){
                $value = null;
            } else {
                $value = $data[$fieldName];
            }

            $values[':'.$fieldName] = $value;

            if($i+1 !== $fieldCount) $query .= ', ';
        }

        $stmt = $this->pdo->prepare($query);
        foreach($values as $parameter => &$value){
            $stmt->bindParam($parameter, $value);
        }

        try{
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch(PDOException $e){
            // TODO: Error exception
        }

    }

    /**
     * @param $id
     * @return array
     */
    public function readRow($id)
    {
        $output = array();
        foreach($this->fields as $field){
            if($field->isPrimaryKey()){
                $query = 'SELECT * FROM '.$this->table.' WHERE `'.$field->getName().' = ?';
                $stmt = $this->pdo->prepare($query);
                $success = $stmt->execute(array($id));
                if($success){
                    $output = $stmt->fetch(PDO::FETCH_ASSOC);
                    break;
                }
            }
        }
        return $output;
    }

    /**
     * @param array $data
     * @param int $id
     * @return void
     */
    public function updateRow(array $data, $id)
    {
        $query = 'UPDATE '.$this->table.' SET';

        $fieldCount = count($this->fields);
        $values = array();
        foreach($this->fields as $i => $field){

            $fieldName = $field->getName();

            $query .= $fieldName.'=:'.$fieldName;

            if($field->isRequired() && empty($data[$fieldName])){
                throw new InvalidArgumentException('Table field "'.$field->getName().'" is required, but received no data.');
            }

            if(empty($data[$fieldName]) && $field->getDefaultValue()){
                $value = $field->getDefaultValue();
            } elseif(empty($data[$fieldName])){
                $value = null;
            } else {
                $value = $data[$fieldName];
            }

            $values[':'.$fieldName] = $value;

            if($i+1 !== $fieldCount) $query .= ', ';
        }

        $query .= ' WHERE `id`==:update_id';
        $values[':update_id'] = $id;

        $stmt = $this->pdo->prepare($query);
        foreach($values as $parameter => &$value){
            $stmt->bindParam($parameter, $value);
        }

        try{
            $stmt->execute();
        } catch(PDOException $e){
            // TODO: Error exception
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteRow($id)
    {
        foreach($this->fields as $field){
            if($field->isPrimaryKey()){
                $query = 'DELETE FROM '.$this->table.' WHERE `'.$field->getName().'` = ?';
                $stmt = $this->pdo->prepare($query);
                $success = $stmt->execute(array($id));
                if($success){
                    break;
                }
            }
        }
    }

} 