<?php

use FlyFoundation\Factory;

require_once __DIR__.'/../use-test-app.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class MySqlGenericDataStoreTest extends PHPUnit_Framework_TestCase {
    /**
     * @var \FlyFoundation\Database\MySqlGenericDataStore
     */
    private $dataStore;

    protected function setUp()
    {
        $this->dataStore = Factory::load("\\FlyFoundation\\Database\\MySqlGenericDataStore");

        $this->dataStore->getMySqlDatabase()->exec(
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

        $stmt = $this->dataStore->getMySqlDatabase()->query("SELECT * FROM generic_datastore_test WHERE id=".$res." LIMIT 1");
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

    public function testUpdateEntry()
    {
        $this->dataStore->getMySqlDatabase()->exec(
            "INSERT INTO
                generic_datastore_test
                (id, `name`, description, adate)
                VALUES
                (1, 'TEST', 'More testing', '2014-06-15 13:33:22')"
        );

        $this->dataStore->updateEntry([
            "id" => 1,
            "name" => "My name!",
            "adate" => new DateTime("2014-08-09 23:59:59")
        ]);

        $stmt = $this->dataStore->getMySqlDatabase()->query("SELECT * FROM generic_datastore_test");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(1,$res["id"]);
        $this->assertEquals("My name!",$res["name"]);
        $this->assertEquals("More testing",$res["description"]);
        $this->assertEquals("2014-08-09 23:59:59",$res["adate"]);
    }

    public function testDeleteEntry()
    {
        $this->dataStore->getMySqlDatabase()->exec(
            "INSERT INTO
                generic_datastore_test
                (id, `name`, description, adate)
                VALUES
                (4, 'TEST', 'More testing', '2014-06-15 13:33:22')"
        );

        $stmt = $this->dataStore->getMySqlDatabase()->query("SELECT * FROM generic_datastore_test WHERE id=4");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($res);

        $this->dataStore->deleteEntry([
            "id" => 4
        ]);

        $stmt = $this->dataStore->getMySqlDatabase()->query("SELECT * FROM generic_datastore_test WHERE id=4");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEmpty($res);
    }

    public function testContainsEntry()
    {
        $this->dataStore->getMySqlDatabase()->exec(
            "INSERT INTO
                generic_datastore_test
                (id, `name`, description, adate)
                VALUES
                (4, 'TEST', 'More testing', '2014-06-15 13:33:22')"
        );

        $stmt = $this->dataStore->getMySqlDatabase()->query("SELECT * FROM generic_datastore_test WHERE id=4");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($res);

        $res = $this->dataStore->containsEntry([
            "id" => 4
        ]);
        $res2 = $this->dataStore->containsEntry([
            "id" => 5
        ]);

        $this->assertTrue($res);
        $this->assertFalse($res2);
    }

    //TODO: Testing of variations, edge cases and errors...
}
 