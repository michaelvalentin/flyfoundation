<?php

use FlyFoundation\Database\GenericDataMapper;
use FlyFoundation\Factory;
use TestApp\Models\GenericTestModel;

require_once __DIR__.'/../use-test-app.php';

class GenericDataMapperTest extends PHPUnit_Framework_TestCase {

    /** @var  GenericDataMapper */
    private $dataMapper;

    protected function setUp(){
        $this->dataMapper = Factory::load("\\FlyFoundation\\Database\\GenericDataMapper");
        $this->dataMapper->setEntityName("\\TestApp\\Models\\GenericTestModel");
        $dataStore = Factory::load("\\TestApp\\Database\\MySqlGenericTestModelDataStore");
        $this->dataMapper->setDataStore($dataStore);
    }

    public function testSaveAndLoad()
    {
        /** @var GenericTestModel $model */
        $model = Factory::load("\\TestApp\\Models\\GenericTestModel");
        $model->set("test","MyTest");
        $model->set("demo","SomeText");
        $this->dataMapper->save($model);
        $this->assertEquals(1, $model->get("id"));

        $model2 = $this->dataMapper->load(["id"=>1]);

        $this->assertEquals(1, $model2->get("id"));
        $this->assertEquals("MyTest", $model2->get("test"));
        $this->assertEquals("SomeText", $model2->get("demo"));
    }

    public function testDelete()
    {
        /** @var GenericTestModel $model */
        $model = Factory::load("\\TestApp\\Models\\GenericTestModel");
        $model->set("test","MyTest");
        $model->set("demo","SomeText");
        $this->dataMapper->save($model);
        $this->assertEquals(1, $model->get("id"));

        $model2 = $this->dataMapper->load(["id"=>1]);

        $this->dataMapper->delete($model2);

        $this->assertNull($model2->get("id"));
        $this->assertNull($model2->get("test"));
        $this->assertNull($model2->get("demo"));

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $model3 = $this->dataMapper->load(["id"=>1]);
    }
}
 