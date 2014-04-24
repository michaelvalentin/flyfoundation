<?php

require_once __DIR__.'/test-init.php';

class DefinitionComponentTestClass extends \FlyFoundation\SystemDefinitions\DefinitionComponent{
    private $customData;
    private $customData2;

    public function getAllCustomData(){
        return $this->customData.$this->customData2;
    }

    public function applyCustomData($customData){
        $this->customData = $customData;
    }

    public function finalize(){
        $this->customData2 = "World";
        if($this->customData == null){
            throw new \FlyFoundation\Exceptions\InvalidArgumentException();
        }
        parent::finalize();
    }
}

class DefinitionComponentTest extends PHPUnit_Framework_TestCase {
    public function testApplyOptions()
    {
        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData" => "Hello"
        ]);
        $definition->finalize();

        $result = $definition->getAllCustomData();

        $this->assertSame("HelloWorld",$result);
    }

    public function testApplyInvalidOption()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");

        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData2" => "Hello"
        ]);
    }

    public function testInvalidComponentOnFinalize()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");

        $definition = new DefinitionComponentTestClass();
        $definition->finalize();
    }

    public function testGetSetting()
    {
        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData" => "Hello",
            "settings" => [
                "test" => "demo",
                "test2" => "hello"
            ]
        ]);
        $definition->finalize();

        $result1 = $definition->getSetting("test");
        $result2 = $definition->getSetting("test2");
        $result3 = $definition->getSetting("test2", "demo");

        $this->assertSame("demo",$result1);
        $this->assertSame("hello",$result2);
        $this->assertSame("hello",$result3);
    }

    public function testGettingNonExistingSetting()
    {
        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData" => "Hello"
        ]);
        $definition->finalize();

        $result = $definition->getSetting("test");
        $result2 = $definition->getSetting("test2", "demo");

        $this->assertNull($result);
        $this->assertSame("demo",$result2);
    }

    public function testGettingSettingsBeforeFinalized()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidOperationException");
        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData" => "Hello",
            "settings" => [
                "test" => "demo",
                "test2" => "hello"
            ]
        ]);

        $result1 = $definition->getSetting("test");
    }

    public function testIsFinalized()
    {
        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData" => "Hello",
            "settings" => [
                "test" => "demo",
                "test2" => "hello"
            ]
        ]);
        $this->assertFalse($definition->isFinalized());
        $definition->finalize();
        $this->assertTrue($definition->isFinalized());
    }

    public function testRequireFinalizedWhenFinalized()
    {
        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData" => "Hello",
            "settings" => [
                "test" => "demo",
                "test2" => "hello"
            ]
        ]);
        $definition->finalize();
        $definition->requireFinalized();
        $this->assertTrue(true);
    }

    public function testRequireFinalizedWhenNotFinalized()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidOperationException");
        $definition = new DefinitionComponentTestClass();
        $definition->applyOptions([
            "customData" => "Hello",
            "settings" => [
                "test" => "demo",
                "test2" => "hello"
            ]
        ]);
        $definition->requireFinalized();
    }
}
 