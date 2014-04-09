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

    protected function setUp()
    {
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

    public function testLoadSuccess()
    {
        $result = $this->dataMapper->load(1);
        $expected = [
            'id'            => 1,
            'location_name' => 'Supermarket',
            'region_site'   => 'Tokyo'
        ];
        $this->assertEquals($expected, $result);
    }
}
 