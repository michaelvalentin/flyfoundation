<?php

use FlyFoundation\Core\Context;
use FlyFoundation\Factory;
use FlyFoundation\Models\OpenPersistentEntity;
use TestApp\Models\MyModel;

require_once __DIR__.'/../test-init.php';


class ModelFactoryTest extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies("testing",new Context());
        parent::setUp();
    }


    /**
     * load
     *  - Loads a model and adds entity definition if it takes that as first constructor parameter
     *  - If no implementation OpenPersistentEntity is used
     */

    //Load implemented model that does not take Entity Definition
    public function testLoadingImplementedModelWihtoutEntityDefinition()
    {
        $result = Factory::loadModel("PlainModel");
        $this->assertInstanceOf("\\TestApp\\Models\\PlainModel",$result);
    }

    //Load implemented model that takes existing Entity Definition
    public function testLoadingImplementedModelWithEntityDefinition()
    {
        /** @var MyModel $result */
        $result = Factory::loadModel("MyModel");
        $this->assertInstanceOf("\\TestApp\\Models\\MyModel",$result);

        $result2 = $result->getDefinition();
        $this->assertInstanceOf("\\FlyFoundation\\SystemDefinitions\\EntityDefinition",$result2);
    }

    //Load implemented model that takes not existing Entity Definition
    public function testLoadingImplementedModelWithNotExistingEntityDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidClassException");
        Factory::loadModel("NoEntityDefinitionModel");
    }

    //Load not-implemented model that does not have an Entity Definition
    public function testLoadingNotImplementedModelThatDoesNotHaveEntityDefinition()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidClassException");
        Factory::loadModel("ModelWithoutImplementationOrEntityDefinition");
    }

    //Load not-implemented model that takes existing Entity Definition
    public function testLoadingNotImplementedModelThatHasAnEntityDefinition()
    {
        /** @var OpenPersistentEntity $result */
        $result = Factory::loadModel("DemoEntity");
        $this->assertInstanceOf("\\FlyFoundation\\Models\\OpenPersistentEntity",$result);
        $result2 = $result->getDefinition()->getName();
        $this->assertSame("DemoEntity",$result2);
    }

    /**
     * exists
     *  - Check if the file exists
     *  - OR if there is alternatively and EntityDefinition with this name
     */

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
 