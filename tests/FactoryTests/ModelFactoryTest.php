<?php

use FlyFoundation\Core\Context;
use FlyFoundation\Factory;
use FlyFoundation\Models\OpenGenericEntity;
use FlyFoundation\Models\OpenPersistentEntity;
use TestApp\Models\MyModel;

require_once __DIR__ . '/../use-test-app.php';


class ModelFactoryTest extends PHPUnit_Framework_TestCase {


    //TODO: Add a "get specification" method to entities, to properly investigate weather the right features have been added

    //Load implemented model that does not take Entity Definition
    public function testLoadingImplementedModelWithoutEntityDefinition()
    {
        $result = Factory::loadModel("PlainModel");
        $this->assertInstanceOf("\\TestApp\\Models\\PlainModel",$result);
    }


    //Load not-implemented model that takes existing Entity Definition
    public function testLoadingNotImplementedModelThatHasAnEntityDefinition()
    {
        /** @var OpenGenericEntity $result */
        $result = Factory::loadModel("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Models\\OpenGenericEntity",$result);
        $result2 = $result->getEntityName();
        $this->assertSame("DemoEntity",$result2);
        $result->set("MyField","test");
        $result3 = $result->get("MyField");
        $this->assertEquals("test",$result3);
        $result->set("OtherField",42);
        $result4 = $result->getPersistentData("This is called from the data mapper");
        $this->assertEquals([
            "MyField" => "test",
            "OtherField" => 42
        ],$result4);

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $result->set("NotExistingField",45);
    }

    public function testDemoEntityValidation()
    {
        /** @var OpenGenericEntity $result */
        $result = Factory::loadModel("DemoEntity");
        $firstRun = $result->validate();
        $result->set("MyField","testing");
        $secondRun = $result->validate();

        $this->assertFalse($firstRun);
        $this->assertTrue($secondRun);
    }

    public function testDemoEntityValidationFail()
    {
        /** @var OpenGenericEntity $result */
        $result = Factory::loadModel("DemoEntity");
        $firstRun = $result->validate();
        $result->set("MyField","test");
        $secondRun = $result->validate();

        $this->assertFalse($firstRun);
        $this->assertFalse($secondRun);
    }

    //Check existence of implemented model
    public function testExistenceOfImplementedModel()
    {
        $result = Factory::modelExists("PlainModel");
        $this->assertTrue($result);
    }

    //Check existence of not-implemented model with existing Entity Definition
    public function testExistenceOfNotImplementedModelWithEntityDefinition()
    {
        $result = Factory::modelExists("DemoEntity");
        $this->assertTrue($result);
    }

    //Check existence of not-implemented model without Entity Definition
    public function testExistenceOfNotImplementedModelWithoutEntityDefinition()
    {
        $result = Factory::modelExists("ModelNotImplementedAndNoEntityDefinition");
        $this->assertFalse($result);
    }

}
 