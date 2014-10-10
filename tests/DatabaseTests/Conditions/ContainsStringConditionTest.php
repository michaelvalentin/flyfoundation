<?php



namespace TestApp\Database\Conditions;
use FlyFoundation\Database\Conditions\ContainsStringCondition;

require_once __DIR__.'/../../test-init.php';


class ContainsStringConditionTest extends \PHPUnit_Framework_TestCase {

    public function testValid()
    {
        $cond = new ContainsStringCondition();
        $cond->setFieldNames(["demo"]);
        $cond->setSearchString("test");

        $result = $cond->readyForUse();

        $this->assertTrue($result);
    }


    public function testNoField()
    {
        $cond = new ContainsStringCondition();
        $cond->setSearchString("Test");

        $result = $cond->readyForUse();

        $this->assertFalse($result);
    }

    public function testTooManyFields()
    {
        $cond = new ContainsStringCondition();
        $cond->setFieldNames(["demo","test"]);
        $cond->setSearchString("test");

        $result = $cond->readyForUse();

        $this->assertFalse($result);
    }

    public function testNoValue()
    {
        $cond = new ContainsStringCondition();
        $cond->setFieldNames(["demo"]);

        $result = $cond->readyForUse();

        $this->assertFalse($result);
    }

    public function testSetGetSearchString()
    {
        $cond = new ContainsStringCondition();
        $cond->setSearchString("testingHere");

        $result = $cond->getSearchString();

        $this->assertEquals("testingHere",$result);
    }

    public function testSetSearchStringToNoneString()
    {
        $cond = new ContainsStringCondition();

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $cond->setSearchString(new \DateTime());
    }

    public function testSetSearchStringToNoneString2()
    {
        $cond = new ContainsStringCondition();

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $cond->setSearchString(15);
    }
}
 