<?php

use FlyFoundation\Core\Context;
use FlyFoundation\Factory;
use FlyFoundation\Models\OpenGenericEntity;
use FlyFoundation\Models\OpenPersistentEntity;
use TestApp\Models\MyModel;

require_once __DIR__ . '/../use-test-app.php';


class ModelFactoryTest extends PHPUnit_Framework_TestCase {


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
 