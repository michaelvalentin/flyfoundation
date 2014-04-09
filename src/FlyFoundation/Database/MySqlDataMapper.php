<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\Database;


use FluentPDO;
use FlyFoundation\Core\Environment;
use FlyFoundation\Exceptions\InvalidArgumentException;
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
     * @return int
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function save($data)
    {
        $tableName = $this->entityDefinition->getTableName();
        $fields = $this->entityDefinition->getFields();
        $isUpdate = FALSE;

        foreach($data as $dataKey => $dataValue){
            $isFound = FALSE;

            foreach($fields as $field){
                if($field->getDatabaseName() == $dataKey){ $isFound = TRUE; break; }
            }

            if(!$isFound){
                $dataItem = '[' . $dataKey . ' => ' . $dataValue . ']';
                throw new InvalidArgumentException(
                    'Could not save data '
                    . $dataItem
                    . ' to the table '
                    . $tableName
                    . ', because the column '
                    . $dataKey
                    . ' does not exist.'
                );
            }
        }

        if(isset($data['id'])){
            $isUpdate = TRUE;
            $query = $this->fpdo->update($tableName, $data);
        } else {
            $data['id'] = NULL;
            $query = $this->fpdo->insertInto($tableName, $data);
        }
        $result = $query->execute();

        if(!$result){
            $dataString = '[' . implode(', ', $data) . ']';
            throw new InvalidArgumentException(
                'Could not save data to the table ' . $tableName . ', using data ' . $dataString . ' in the database.'
            );
        }

        return $isUpdate ? $data['id'] : $result;
    }

    /**
     * @param integer $id
     *
     * @return void
     * @throws \FlyFoundation\Exceptions\InvalidArgumentException
     */
    public function delete($id)
    {
        $tableName = $this->entityDefinition->getTableName();

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
     * @return array
     */
    public function load($id)
    {
        $className = $this->entityDefinition->getClassName();
        $tableName = $this->entityDefinition->getTableName();

        $result = $this->fpdo->from($tableName, $id)->fetch();

        if(!$result){
            throw new InvalidArgumentException(
                'Could not load a row from the table ' . $tableName . ' with the ID ' . $id . ' in the database.'
            );
        }

        return $result;
    }
}