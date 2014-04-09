<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Database;


use FluentPDO;
use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\InvalidArgumentException;
use FlyFoundation\Models\Model;
use FlyFoundation\SystemDefinitions\EntityDefinition;

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
       $config = $this->getConfig();
       $pdo = new PDO(
           'mysql:dbname=' . $config->get('database_name') . ';host=' . $config->get('database_host'),
           $config->get('database_user'),
           $config->get('database_password')
       );
       $this->fpdo = new FluentPDO($pdo);
       $this->entityDefinition = $entityDefinition;
    }

    /**
     * @param array $data
     *
     * @return bool|int|void
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function save($data)
    {
        $tableName = $this->entityDefinition->getDatabaseName();

        if(isset($data['id'])){
            $query = $this->fpdo->update($tableName, $data);
        } else {
            $data['id'] = NULL;
            $query = $this->fpdo->insertInto($tableName, $data);
        }

        $result = $query->execute();

        if(!$result){
            $dataString = '[' . implode(', ', $data) . ']';
            throw new InvalidArgumentException(
                'Could not save a row to the table ' . $tableName . ' with the data ' . $dataString . ' in the database'
            );
        }

        return $result;
    }

    /**
     * @param integer $id
     *
     * @return bool
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function delete($id)
    {
        $tableName = $this->entityDefinition->getDatabaseName();

        $result = $this->fpdo->delete($tableName, $id)->execute();

        if(!$result){
            throw new InvalidArgumentException(
                'Could not delete a row from the table ' . $tableName . ' with the ID ' . $id . ' in the database.'
            );
        }
    }

    /**
     * @param $id
     *
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     * @return Model
     */
    public function load($id)
    {
        $className = $this->entityDefinition->getName();
        $tableName = $this->entityDefinition->getDatabaseName();

        $result = $this->fpdo->from($tableName, $id)->fetch();

        if(!$result){
            throw new InvalidArgumentException(
                'Could not load a row from the table ' . $tableName . ' with the ID ' . $id . ' in the database.'
            );
        }

        $factory = $this->getFactory();
        $model  = $factory->loadModel($className, [$this->entityDefinition,  $result]);

        return $model;
    }
}