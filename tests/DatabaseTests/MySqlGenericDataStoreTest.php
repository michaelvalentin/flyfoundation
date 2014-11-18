<?php

require_once __DIR__.'/../test-init.php';

class MySqlGenericDataStoreTest extends PHPUnit_Framework_TestCase {
    /**
     * @var \FlyFoundation\Database\MySqlGenericDataStore
     */
    private $dataStore;

    protected function setUp()
    {
        $this->dataStore = new \FlyFoundation\Database\MySqlGenericDataStore();

        $testDatabase = json_decode(file_get_contents(__DIR__."/../mysql-test-database.json"),true);
        $testPDO = new PDO(
            "mysql:host=".$testDatabase["host"].";dbname=".$testDatabase["database"],
            $testDatabase["user"],
            $testDatabase["password"]);
        $this->dataStore->setMySqlDatabase($testPDO);
        $testPDO->exec(
            "CREATE TABLE IF NOT EXISTS `generic_datastore_test` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text,
                `adate` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
        );

        $this->dataStore->setName("generic_datastore_test");
        $field1 = new \FlyFoundation\Database\Fields\IntegerField();
        $field1->setInIdentifier();
        $field1->setAutoIncrement();
        $field1->setRequired();
        $field1->setName("id");
        $field2 = new \FlyFoundation\Database\Fields\TextField();
        $field2->setName("name");
        $field2->setMaxLength(255);
        $field2->setRequired();
        $field3 = new \FlyFoundation\Database\Fields\TextField();
        $field3->setName("description");
        $field4 = new \FlyFoundation\Database\Fields\DateTimeField();
        $field4->setName("adate");
        $field4->setRequired();
        $this->dataStore->addField($field1);
        $this->dataStore->addField($field2);
        $this->dataStore->addField($field3);
        $this->dataStore->addField($field3);
        $this->dataStore->addField($field4);
    }

    protected function tearDown()
    {
        $pdo = $this->dataStore->getMySqlDatabase();
        $pdo->exec("DROP TABLE generic_datastore_test;");
    }

    public function testCreateEntry()
    {
        $res = $this->dataStore->createEntry(
            [
                "name" => "This is a test",
                "description" => "Testing it out with a longer text here æøå",
                "adate" => new DateTime("2014-01-09 14:55:43")
            ]
        );

        $this->assertEquals(1,$res);

        $stmt = $this->dataStore->getMySqlDatabase()->query("SELECT * FROM generic_datastore_test");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals("This is a test",$res["name"]);
        $this->assertEquals("Testing it out with a longer text here æøå",$res["description"]);
        $this->assertEquals("2014-01-09 14:55:43",$res["adate"]);
    }

    public function testReadEntry()
    {
        $this->dataStore->getMySqlDatabase()->exec(
            "INSERT INTO
                generic_datastore_test
                (id, `name`, description, adate)
                VALUES
                (1, 'TEST', 'More testing', '2014-06-15 13:33:22')"
        );

        $res = $this->dataStore->readEntry(["id" => 1]);

        $this->assertEquals(1, $res["id"]);
        $this->assertEquals("TEST", $res["name"]);
        $this->assertEquals("More testing",$res["description"]);
        $compDate = new DateTime("2014-06-15 13:33:22");
        $this->assertEquals($compDate->getTimestamp(), $res["adate"]->getTimeStamp(), '', 0.5);
    }
}
 