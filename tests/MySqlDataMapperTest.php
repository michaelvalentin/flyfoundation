<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

require_once __DIR__.'/test-init.php';

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

    /** @var OpenPersistentEntity $insertEntity */
    public $insertEntity;

    /** @var OpenPersistentEntity $updateEntity */
    public $updateEntity;

    /** @var OpenPersistentEntity $deleteEntity */
    public $deleteEntity;

    /** @var OpenPersistentEntity $loadEntity */
    public $loadEntity;

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
        $pdo->query("INSERT INTO `local_region_site` VALUES (1, 'Loc 1', 'Ins');")->execute();

        $this->entityDefinition = new EmptyEntityDefinition();
        $this->entityDefinition->setTableName('local_region_site');

        $fieldA = new EntityField('id', EntityFieldType::INTEGER);
        $fieldB = new EntityField('location_name', EntityFieldType::STRING);
        $fieldC = new EntityField('region_site', EntityFieldType::STRING);

        $this->entityDefinition->addField($fieldA);
        $this->entityDefinition->addField($fieldB);
        $this->entityDefinition->addField($fieldC);

        $this->insertEntity = new OpenPersistentEntity($this->entityDefinition, ['location_name' => 'Loc 2', 'region_site' => 'Ins']);
        $this->updateEntity = new OpenPersistentEntity($this->entityDefinition, ['id' => 1, 'location_name' => 'Loc 2', 'region_site' => 'Upd']);
        $this->deleteEntity = new OpenPersistentEntity($this->entityDefinition, ['id' => 1, 'location_name' => 'Loc 1', 'region_site' => 'Ins']);
        $this->loadEntity = new OpenPersistentEntity($this->entityDefinition, ['id' => 1, 'location_name' => 'Loc 1', 'region_site' => 'Ins']);

        $config = new Config();
        $settings = json_decode(file_get_contents(__DIR__."/mysql-test-database.json"),true);
        $config->setMany([
             "database_host" => $settings["host"],
             "database_user" => $settings["user"],
             "database_password" => $settings["password"],
             "database_name" => $settings["database"]
        ]);
        $context = new Context();
        $factory = new Factory($config, $context);

        $this->dataMapper = new MySqlDataMapper($this->entityDefinition);
        $this->dataMapper->setConfig($config);
        $this->dataMapper->setContext($context);
        $this->dataMapper->setFactory($factory);
        parent::setUp();
    }

    public function testLoadEntity()
    {
        $this->setExpectedException('FlyFoundation\Exceptions\UnknownClassException');
        $expected = $this->loadEntity;
        $result = $this->dataMapper->load($expected->get('id'));
        $this->assertEquals($expected, $result);
    }

    public function testLoadUnknownEntity()
    {
        $this->setExpectedException('FlyFoundation\Exceptions\InvalidArgumentException');
        $this->dataMapper->load(2);
    }

    public function testLoadInvalidType()
    {
        $this->setExpectedException('FlyFoundation\Exceptions\InvalidArgumentException');
        $this->dataMapper->load('Not integer');
    }

    public function testDeleteEntity()
    {
        $this->dataMapper->delete($this->deleteEntity->get('id'));
    }

    public function testDeleteUnknownEntity()
    {
        $this->dataMapper->delete(2);
    }

    public function testDeleteInvalidType()
    {
        $this->setExpectedException('FlyFoundation\Exceptions\InvalidArgumentException');
        $this->dataMapper->delete('Not integer');
    }

    public function testSaveInsertEntity()
    {
        $expected = 2;
        $result = $this->dataMapper->save($this->insertEntity);
        $this->assertEquals($expected, $result);
    }

    public function testSaveUpdateEntity()
    {
        $expected = $this->updateEntity->get('id');
        $result = $this->dataMapper->save($this->updateEntity);
        $this->assertEquals($expected, $result);
    }

    public function testSaveUpdateInvalidId()
    {
        $this->setExpectedException('FlyFoundation\Exceptions\InvalidArgumentException');
        $this->updateEntity->set('id', 'Not Integer');
        $this->dataMapper->save($this->updateEntity);
    }
}
