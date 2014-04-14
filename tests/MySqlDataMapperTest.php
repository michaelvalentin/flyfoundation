<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

require_once __DIR__.'/../vendor/autoload.php';

use FlyFoundation\Config;
use FlyFoundation\Core\Context;
use FlyFoundation\Database\MySqlDataMapper;
use FlyFoundation\Factory;
use FlyFoundation\Models\OpenPersistentEntity;
use FlyFoundation\SystemDefinitions\EmptyEntityDefinition;
use FlyFoundation\SystemDefinitions\EntityField;
use FlyFoundation\SystemDefinitions\EntityFieldType;

class MySqlDataMapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var MySqlDataMapper $dataMapper */
    public $dataMapper;
    public $entityDefinition;

    /** @var OpenPersistentEntity[] $insertEntities */
    public $insertEntities;
    public $insertIds;
    public $updateData;
    public $deleteId;

    public static function setUpBeforeClass()
    {
        $pdo = new PDO("mysql:dbname=flyfoundation_test", "root", "root");
        $pdo->query('TRUNCATE TABLE local_region_site')->execute();
    }

    protected function setUp()
    {
        $this->entityDefinition = new EmptyEntityDefinition();
        $this->entityDefinition->setTableName('local_region_site');

        $fieldA = new EntityField('id', EntityFieldType::INTEGER);
        $fieldA->setPrimaryKey(true);

        $fieldB = new EntityField('location_name', EntityFieldType::STRING);
        $fieldC = new EntityField('region_site', EntityFieldType::STRING);

        $this->entityDefinition->addField($fieldA);
        $this->entityDefinition->addField($fieldB);
        $this->entityDefinition->addField($fieldC);

        $entityA = new OpenPersistentEntity($this->entityDefinition, ['location_name' => 'Loc 1', 'region_site' => 'Ins']);
        $entityB = new OpenPersistentEntity($this->entityDefinition, ['location_name' => 'Loc 2', 'region_site' => 'Ins']);
        $entityC = new OpenPersistentEntity($this->entityDefinition, ['location_name' => 'Loc 3', 'region_site' => 'Ins']);

        $this->insertEntities = [$entityA, $entityB, $entityC];

        $config = new Config();
        $config->setMany([
             "database_host" => "localhost",
             "database_user" => "root",
             "database_password" => "root",
             "database_name" => "flyfoundation_test"
        ]);
        $context = new Context();
        $factory = new Factory($config, $context);

        $this->dataMapper = new MySqlDataMapper($this->entityDefinition);
        $this->dataMapper->setConfig($config);
        $this->dataMapper->setContext($context);
        $this->dataMapper->setFactory($factory);
        parent::setUp();
    }

    public function testSaveInsertSuccess()
    {
        $returnedIds = [];
        foreach($this->insertEntities as $entity){
            $returnedIds[] = $this->dataMapper->save($entity)['id'];
        }

        $this->assertCount(3, $returnedIds);
        $this->assertEquals([1,2,3], $returnedIds);
    }

    public function testSaveUpdateSuccess()
    {
        $entity = $this->insertEntities[1];
        $entity->set('id', 2);
        $entity->set('region_site', 'Upd');
        $returnedId = $this->dataMapper->save($entity);
        $this->assertEquals(['id' => 2], $returnedId);
    }

    public function testLoadSuccess()
    {
        $entity = $this->insertEntities[0];
        $entity->set('id', 1);
        $result = $this->dataMapper->load(1);
        $this->assertEquals($entity, $result);
    }

    public function testDeleteSuccess()
    {
        $this->dataMapper->delete(3);

        $this->setExpectedException("FlyFoundation\Exceptions\InvalidArgumentException");
        $this->dataMapper->load(3);
    }

    public function testLoadFailure()
    {
        $this->setExpectedException("FlyFoundation\Exceptions\InvalidArgumentException");
        $this->dataMapper->load(4);
    }

    public function testDeleteFailure()
    {
        $this->setExpectedException("FlyFoundation\Exceptions\InvalidArgumentException");
        $this->dataMapper->delete(4);
    }
}
