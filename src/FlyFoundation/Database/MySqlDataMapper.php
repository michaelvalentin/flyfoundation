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
use PDO;

class MySqlDataMapper implements DataMapper
{
    use Environment;

    /**
     * @var FluentPDO
     */
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
     * @return array
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function save(PersistentEntity $persistentEntity)
    {
        $tableName = $this->entityDefinition->getTableName();
        $entityColumns = $persistentEntity->getPersistentData();
        $primaryKey = $persistentEntity->getPrimaryKey();

        $query = $this->getPdo()->insertInto($tableName, $entityColumns)->onDuplicateKeyUpdate($entityColumns);
        $result = $query->execute();

        return $primaryKey ? $primaryKey : ['id' => $result];

    }

    /**
     * @param int | array $primaryKey
     *
     * @return void
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function delete($primaryKey)
    {
        $tableName = $this->entityDefinition->getTableName();

        if(is_int($primaryKey)){

            $query = $this->getPdo()->delete($tableName, $primaryKey);

        } elseif(is_array($primaryKey)){

            $definedPrimaryKey = $this->entityDefinition->getPrimaryKey();
            $diff = array_diff_key($definedPrimaryKey, $primaryKey);
            if(!empty($diff)){
                throw new InvalidArgumentException(
                    'Failed to delete data from ' . $tableName
                    . ', because the keys in $primaryKey did not match the entity definition\'s primary key columns.'
                );
            }
            $query = $this->getPdo()->delete($tableName)->where($primaryKey);

        } else {
            throw new InvalidArgumentException(
                'Failed to delete data from ' . $tableName
                . ', because the type of $primaryKey was ' . gettype($primaryKey) . ', expected integer or array.'
            );
        }

        $data = $query->execute();
        if(!$data){
            // TODO throw warning if data is already deleted
        }
    }

    /**
     * @param int | array $primaryKey
     *
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     * @return PersistentEntity
     */
    public function load($primaryKey)
    {
        $tableName = $this->entityDefinition->getTableName();

        if(is_int($primaryKey)){

            $query = $this->getPdo()->from($tableName, $primaryKey);

        } elseif(is_array($primaryKey)){

            $definedPrimaryKey = $this->entityDefinition->getPrimaryColumnTypePairs();
            $diff = array_diff_key($definedPrimaryKey, $primaryKey);
            if(!empty($diff)){
                throw new InvalidArgumentException(
                    'Failed to load data from ' . $tableName
                    . ', because the keys in $primaryKey did not match the entity definition\'s primary key columns.'
                );
            }
            $query = $this->getPdo()->from($tableName)->where($primaryKey);

        } else {
            throw new InvalidArgumentException(
                'Failed to load data from ' . $tableName
                . ', because the type of $primaryKey was ' . gettype($primaryKey) . ', expected integer or array.'
            );
        }

        $data = $query->fetchAll();
        if(empty($data)){
            throw new InvalidArgumentException(
                'Failed to load data from ' . $tableName
                . ', because the query did not find any data matching $primaryKey.'
            );
        }

        $factory = $this->getFactory();
        return $factory->loadModel($this->entityDefinition->getClassName(), $data);
    }
}