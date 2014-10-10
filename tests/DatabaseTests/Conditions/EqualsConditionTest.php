<?php



namespace TestApp\Database\Conditions;
use DateTime;
use FlyFoundation\Database\Conditions\EqualsCondition;

require_once __DIR__.'/../../test-init.php';


class EqualsConditionTest extends \PHPUnit_Framework_TestCase {

    public function testValid()
    {
        $cond = new EqualsCondition();
        $cond->setFieldNames(["demo"]);
        $cond->setRequiredValue("test");

        $result = $cond->readyForUse();

        $this->assertTrue($result);
    }

    public function testValid2()
    {
        $cond = new EqualsCondition();
        $cond->setFieldNames(["demo"]);
        $cond->setRequiredValue(142);

        $result = $cond->readyForUse();

        $this->assertTrue($result);
    }

    public function testValid3()
    {
        $cond = new EqualsCondition();
        $cond->setFieldNames(["demo"]);
        $cond->setRequiredValue(new DateTime());

        $result = $cond->readyForUse();

        $this->assertTrue($result);
    }

    public function testNoField()
    {
        $cond = new EqualsCondition();
        $cond->setRequiredValue("Test");

        $result = $cond->readyForUse();

        $this->assertFalse($result);
    }

    public function testTooManyFields()
    {
        $cond = new EqualsCondition();
        $cond->setFieldNames(["demo","test"]);
        $cond->setRequiredValue("test");

        $result = $cond->readyForUse();

        $this->assertFalse($result);
    }

    public function testNoValue()
    {
        $cond = new EqualsCondition();
        $cond->setFieldNames(["demo"]);

        $result = $cond->readyForUse();

        $this->assertFalse($result);
    }

    public function testSetGetSearchString()
    {
        $cond = new EqualsCondition();
        $cond->setRequiredValue("testingSomethingHere");

        $result = $cond->getRequiredValue();

        $this->assertEquals("testingSomethingHere",$result);
    }

    public function testSetSearchStringToObject()
    {
        $cond = new EqualsCondition();

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $cond->setRequiredValue(new EqualsCondition());
    }

    public function testSetSearchStringToNoneArray()
    {
        $cond = new EqualsCondition();

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $cond->setRequiredValue(["test","demo"]);
    }
}
 