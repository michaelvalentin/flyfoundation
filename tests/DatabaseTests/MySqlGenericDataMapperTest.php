<?php

use FlyFoundation\Database\MySqlGenericDataMapper;
use FlyFoundation\Factory;
use FlyFoundation\Models\OpenPersistentEntity;

require_once __DIR__.'/../test-init.php';


class MySqlGenericDataMapperTest extends PHPUnit_Framework_TestCase {

    /** @var Factory */
    private $factory;
    /** @var  MySqlGenericDataMapper */
    private $dataMapper;

    public static function getPdo()
    {
        $settings = json_decode(file_get_contents(TEST_BASE."/mysql-test-database.json"),true);
        $pdo = new PDO("mysql:host=".$settings["host"].";dbname=".$settings["database"], $settings["user"], $settings["password"]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    public static function setUpBeforeClass()
    {
        self::getPdo()->exec("DROP TABLE IF EXISTS `data_mapper_test`; CREATE TABLE `data_mapper_test` (`id` int NOT NULL PRIMARY KEY AUTO_INCREMENT, `string_field` VARCHAR(255) NOT NULL,  `text_field` TEXT NOT NULL, `datetime_field` DATETIME NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        parent::setUpBeforeClass();
    }

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $context = new \FlyFoundation\Core\Context();
        $this->factory = $app->getFactory($context);

        $this->dataMapper = $this->factory->loadDataMapper("DataMapperTest");

        self::getPdo()->exec("TRUNCATE TABLE `data_mapper_test`; INSERT INTO `data_mapper_test` (string_field, text_field, datetime_field) VALUES ('Test','Longer text','2014-01-01 10:43:57'),('Test2','Other Longer text','2014-04-22 17:23:24')");

        parent::setUp();
    }

    public static function tearDownAfterClass()
    {
        self::getPdo()->exec("DROP TABLE IF EXISTS `data_mapper_test`");
        parent::tearDownAfterClass();
    }

    public function testTestSetup()
    {
        $rows = self::getPdo()->query("SELECT * FROM `data_mapper_test`")->fetchAll();
        $this->assertSame(1,$rows[0]["id"]);
        $this->assertSame("Test",$rows[0]["string_field"]);
        $this->assertSame("Longer text",$rows[0]["text_field"]);
        $this->assertSame("2014-01-01 10:43:57", $rows[0]["datetime_field"]);
        $this->assertSame(2,$rows[1]["id"]);
        $this->assertSame("Test2",$rows[1]["string_field"]);
        $this->assertSame("Other Longer text",$rows[1]["text_field"]);
        $this->assertSame("2014-04-22 17:23:24", $rows[1]["datetime_field"]);
    }

    public function testSaveValid()
    {
        $entity = $this->factory->loadModel("DataMapperTest",[[
            "StringField" => "Test",
            "TextField" => "Test data here",
            "DatetimeField" => "2014-05-20 17:50:00"
        ]]);

        $this->assertTrue($entity->isValid());

        $id = $this->dataMapper->save($entity);

        $rows = self::getPdo()->query("SELECT * FROM `data_mapper_test`")->fetchAll();
        $this->assertSame($id,$rows[2]["id"]);
        $this->assertSame("Test",$rows[2]["string_field"]);
        $this->assertSame("Test data here",$rows[2]["text_field"]);
        $this->assertSame("2014-05-20 17:50:00", $rows[2]["datetime_field"]);
    }

    public function testSaveInvalid()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidEntityException");
        $entity = $this->factory->loadModel("DataMapperTest",[[
            "StringField" => "Test",
            "TextField" => "Test data here"
        ]]);

        $this->assertFalse($entity->isValid());

        $id = $this->dataMapper->save($entity);
    }

    public function testLoad()
    {
        /** @var OpenPersistentEntity $entity */
        $entity = $this->dataMapper->load(2);
        $this->assertSame(2,$entity->get("Id"));
        $this->assertSame("Test2",$entity->get("StringField"));
        $this->assertSame("Other Longer text",$entity->get("TextField"));
        $this->assertSame("2014-04-22 17:23:24", $entity->get("DatetimeField")->format('Y-m-d H:i:s'));
    }
}
 