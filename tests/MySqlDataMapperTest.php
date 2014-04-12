<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

require_once __DIR__.'/test-init.php';

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
        $settings = json_decode(file_get_contents(__DIR__."/mysql-test-database.json"),true);
        $pdo = new PDO("mysql:host=".$settings["host"].";dbname=".$settings["database"], $settings["user"], $settings["password"]);
        $pdo->query('DROP TABLE local_region_site')->execute();
        $pdo->query("CREATE TABLE IF NOT EXISTS `local_region_site` (`id` int NOT NULL PRIMARY KEY AUTO_INCREMENT, `location_name` varchar(255) NOT NULL,  `region_site` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->execute();
    }

    protected function setUp()
    {
        $settings = json_decode(file_get_contents(__DIR__."/mysql-test-database.json"),true);
        $pdo = new PDO("mysql:host=".$settings["host"].";dbname=".$settings["database"], $settings["user"], $settings["password"]);
        $pdo->query("TRUNCATE TABLE `local_region_site`")->execute();


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
        $settings = json_decode(file_get_contents(__DIR__."/mysql-test-database.json"),true);
        $config->setMany([
             "database_host" => $settings["host"],
             "database_user" => $settings["user"],
             "database_password" => $settings["password"],
             "database_name" => $settings["database"]
        ]);

        $this->dataMapper = new MySqlDataMapper($this->entityDefinition);
        $this->dataMapper->setConfig($config);
        parent::setUp();
    }

    public function testMockTest(){
        $this->assertTrue(true);
    }

    /*

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
        $this->testSaveInsertSuccess();
        $returnedId = $this->dataMapper->save($this->updateData);
        $this->assertEquals($this->updateData['id'], $returnedId);
    }

    public function testLoadSuccess()
    {
        $this->testSaveInsertSuccess();
        $expected = $this->insertData[0];
        $expected['id'] = $this->insertIds[0];
        $result = $this->dataMapper->load($this->insertIds[0]);
        $this->assertEquals($expected, $result);
    }

    public function testDeleteSuccess()
    {
        $this->testSaveInsertSuccess();
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

    */
}
