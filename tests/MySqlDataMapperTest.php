<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

require_once __DIR__.'/../vendor/autoload.php';

use FlyFoundation\Config;
use FlyFoundation\Database\MySqlDataMapper;
use FlyFoundation\SystemDefinitions\EmptyEntityDefinition;
use FlyFoundation\SystemDefinitions\EntityField;
use FlyFoundation\SystemDefinitions\EntityFieldType;

class MySqlDataMapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var MySqlDataMapper $dataMapper */
    public $dataMapper;
    public $entityDefinition;
    public $insertData;
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

        $this->insertData = [
            ['location_name' => 'Loc 1', 'region_site' => 'Ins'],
            ['location_name' => 'Loc 2', 'region_site' => 'Ins'],
            ['location_name' => 'Loc 3', 'region_site' => 'Ins']
        ];

        $this->insertIds = [1,2,3];
        $this->updateData = ['id' => 2, 'location_name' => 'Loc 2', 'region_site' => 'Upd'];
        $this->deleteId = 3;

        $this->entityDefinition = new EmptyEntityDefinition();
        $this->entityDefinition->setTableName('local_region_site');

        $fieldA = new EntityField('id', EntityFieldType::INTEGER);
        $fieldB = new EntityField('location_name', EntityFieldType::STRING);
        $fieldC = new EntityField('region_site', EntityFieldType::STRING);

        $this->entityDefinition->addField($fieldA);
        $this->entityDefinition->addField($fieldB);
        $this->entityDefinition->addField($fieldC);

        $config = new Config();
        $config->setMany([
             "database_host" => "localhost",
             "database_user" => "root",
             "database_password" => "root",
             "database_name" => "flyfoundation_test"
        ]);

        $this->dataMapper = new MySqlDataMapper($this->entityDefinition);
        $this->dataMapper->setConfig($config);
        parent::setUp();
    }

    public function testSaveInsertSuccess()
    {
        $returnedIds = [];
        foreach($this->insertData as $data){
            $returnedIds[] = $this->dataMapper->save($data);
        }

        $this->assertCount(3, $returnedIds);
        $this->assertEquals($this->insertIds, $returnedIds);
    }

    public function testSaveUpdateSuccess()
    {
        $returnedId = $this->dataMapper->save($this->updateData);
        $this->assertEquals($this->updateData['id'], $returnedId);
    }

    public function testLoadSuccess()
    {
        $expected = $this->insertData[0];
        $expected['id'] = $this->insertIds[0];
        $result = $this->dataMapper->load($this->insertIds[0]);
        $this->assertEquals($expected, $result);
    }

    public function testDeleteSuccess()
    {
        $this->dataMapper->delete($this->insertIds[2]);

        $this->setExpectedException("FlyFoundation\Exceptions\InvalidArgumentException");
        $this->dataMapper->load($this->insertIds[2]);
    }

    public function testSaveTooMuchData()
    {
        $this->setExpectedException("FlyFoundation\Exceptions\InvalidArgumentException");
        $data = $this->insertData[0];
        $data['extra_data'] = 'not allowed';
        $this->dataMapper->save($data);
    }

    public function testSaveInvalidId()
    {
        $this->setExpectedException("FlyFoundation\Exceptions\InvalidArgumentException");
        $data = $this->insertData[0];
        $data['id'] = 4;
        $this->dataMapper->save($data);
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
