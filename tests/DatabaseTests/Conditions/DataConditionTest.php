<?php

namespace Conditions;

use TestApp\Database\Conditions\DemoDataCondition;

require_once __DIR__.'/../../test-init.php';

class DataConditionTest extends \PHPUnit_Framework_TestCase {

    public function testSetGetFieldNames()
    {
        $dataCondition = new DemoDataCondition();
        $dataCondition->setFieldNames([
            "test",
            "demo"
        ]);
        $result = $dataCondition->getFieldNames();
        $this->assertCount(2,$result);
        $this->assertContains("test",$result);
        $this->assertContains("demo",$result);
    }

    public function testSetNoneStringFieldNames()
    {
        $fieldNames = [
            "john",
            new \DateTime()
        ];
        $dataCondition = new DemoDataCondition();

        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $dataCondition->setFieldNames($fieldNames);
    }
}
 