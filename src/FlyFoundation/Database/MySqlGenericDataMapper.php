<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Database;


use FluentPDO;
use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\FlyFoundationException;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Models\PersistentEntity;
use FlyFoundation\SystemDefinitions\EntityDefinition;
use FlyFoundation\SystemDefinitions\EntityField;
use FlyFoundation\Util\NameManipulator;
use PDO;

class MySqlGenericDataMapper implements DataMapper
{
    use Environment;

    /** @var FluentPDO */
    private $fpdo;
    private $entityDefinition;

    public function __construct(EntityDefinition $entityDefinition)
    {
        $this->entityDefinition = $entityDefinition;
    }

    /**
     * @return FluentPDO
     */
    public function getPdo()
    {
        if($this->fpdo !== NULL){
            return $this->fpdo;
        } else {
            $config = $this->getConfig();
            $pdo = new PDO(
                'mysql:dbname=' . $config->get('database_name') . ';host=' . $config->get('database_host'),
                $config->get('database_user'),
                $config->get('database_password')
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->fpdo = new FluentPDO($pdo);
            return $this->fpdo;
        }
    }

    /**
     * @param PersistentEntity $persistentEntity
     *
     * @return integer
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function save(PersistentEntity $persistentEntity)
    {
        $tableName = $this->getTableName($this->entityDefinition);
        $entityColumns = $persistentEntity->getPersistentData();

        $nameManipulator = $this->getFactory()->load("\\FlyFoundation\\Util\\NameManipulator");
        foreach($entityColumns as $column => $value){
            $newColumn = $nameManipulator->toUnderscored($column);
            $entityColumns[$newColumn] = $value;
            unset($entityColumns[$column]);
        }

        $id = isset($entityColumns['id']) ? $entityColumns['id'] : 0;

        if(!is_int($id)){
            throw new InvalidArgumentException(
                'Failed to save data to ' . $tableName
                . ', because the type of the entity\'s id was ' . gettype($id) . ', expected integer.'
            );
        }

        $query = $this->getPdo()->insertInto($tableName, $entityColumns)->onDuplicateKeyUpdate($entityColumns);
        $result = $query->execute();

        return $id ? $id : (int)$result;
    }

    /**
     * @param integer $id
     * @return void
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function delete($id)
    {
        $tableName = $this->getTableName($this->entityDefinition);

        if(is_int($id)){
            $query = $this->getPdo()->delete($tableName, $id);
        } else {
            throw new InvalidArgumentException(
                'Failed to delete data from ' . $tableName
                . ', because the type of $primaryKey was ' . gettype($id) . ', expected integer.'
            );
        }

        $data = $query->execute();
        if(!$data){
            // TODO throw warning if data is already deleted
        }
    }

    /**
     * @param integer $id
     *
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     * @return PersistentEntity
     */
    public function load($id)
    {
        $tableName = $this->getTableName($this->entityDefinition);

        if(is_int($id)){
            $query = $this->getPdo()->from($tableName, $id);
        } else {
            throw new InvalidArgumentException(
                'Failed to load data from ' . $tableName
                . ', because the type of $id was ' . gettype($id) . ', expected integer.'
            );
        }

        $data = $query->fetchAll();
        if(empty($data)){
            throw new InvalidArgumentException(
                'Failed to load data from ' . $tableName
                . ', because the query did not find any data that matches the $id.'
            );
        }

        $factory = $this->getFactory();
        return $factory->loadModel($this->entityDefinition->getName(), [$this->entityDefinition,$data[0]]);
    }

    private function getTableName(EntityDefinition $definition)
    {
        $tableName = $definition->getSetting("database_table",false);
        if($tableName){
            return $tableName;
        }
        /** @var NameManipulator $nameManipulator */
        $nameManipulator = $this->getFactory()->load("\\FlyFoundation\\Util\\NameManipulator");
        return $nameManipulator->toUnderscored($definition->getName());
    }

    private function getColumnName(EntityField $field)
    {
        $columnName = $field->getSetting("database_column",false);
        if($columnName){
            return $columnName;
        }
        $nameManipulator = $this->getFactory()->load("\\FlyFoundation\\Util\\NameManipulator");
        return $nameManipulator->toUnderscored($field->getName());
    }
}