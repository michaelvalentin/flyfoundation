<?php

use FlyFoundation\Database\GenericDataFinder;
use FlyFoundation\Database\GenericDataMapper;
use FlyFoundation\Factory;
use FlyFoundation\Models\OpenGenericEntity;
use TestApp\Models\GenericTestModel;

require_once __DIR__.'/../use-test-app.php';

class GenericDataFinderTest extends PHPUnit_Framework_TestCase {

    /** @var GenericDataFinder */
    private $dataFinder;

    /** @var GenericDataMapper */
    private $dataMapper;

    protected function setUp(){
        $this->dataFinder = Factory::load("\\FlyFoundation\\Database\\GenericDataFinder");
        $this->dataMapper = Factory::load("\\FlyFoundation\\Database\\GenericDataMapper");
        $this->dataFinder->setEntityName("GenericTestModel");
        $this->dataMapper->setEntityName("GenericTestModel");
        $dataStore = Factory::load("\\TestApp\\Database\\GenericTestModelDataStore");
        $this->dataFinder->setDataStore($dataStore);
        $this->dataMapper->setDataStore($dataStore);
    }

    public function testFetch()
    {
        /** @var GenericTestModel $model1 */
        $model1 = Factory::load("\\TestApp\\Models\\GenericTestModel");
        $model1->set("test","MyTest");
        $model1->set("demo","SomeText");

        /** @var GenericTestModel $model2 */
        $model2 = Factory::load("\\TestApp\\Models\\GenericTestModel");
        $model2->set("test","Here is test");
        $model2->set("demo","Here is demo");

        /** @var GenericTestModel $model3 */
        $model3 = Factory::load("\\TestApp\\Models\\GenericTestModel");
        $model3->set("test","Something 1234");
        $model3->set("demo","Other value");

        $this->dataMapper->save($model1);
        $this->dataMapper->save($model2);
        $this->dataMapper->save($model3);

        /** @var OpenGenericEntity[] $result */
        $result = $this->dataFinder->fetch();

        $this->assertEquals("MyTest",$result[0]->get("test"));
        $this->assertEquals("SomeText",$result[0]->get("demo"));

        $this->assertEquals("Here is test",$result[1]->get("test"));
        $this->assertEquals("Here is demo",$result[1]->get("demo"));

        $this->assertEquals("Something 1234",$result[2]->get("test"));
        $this->assertEquals("Other value",$result[2]->get("demo"));

    }
}
 