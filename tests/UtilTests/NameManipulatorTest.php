<?php

use FlyFoundation\Util\NameManipulator;

require_once __DIR__.'/../test-init.php';


class NameManipulatorTest extends PHPUnit_Framework_TestCase {
    /** @var  NameManipulator */
    private $nameManipulator;

    protected function setUp(){
        $this->nameManipulator = new NameManipulator();
    }

    public function testToCamelUpperCaseFirst()
    {
        $cases = [
            "MyClassName" => "MyClassName",
            "myClassName" => "MyClassName",
            "Class" => "Class",
            "class" => "Class",
            "URLShortener" => "URLShortener",
            "myURLShortener" => "MyURLShortener",
            "my_e_d_p_thing" => "MyEDPThing",
            "some_name" => "SomeName",
            "some_name_here" => "SomeNameHere"
        ];
        foreach($cases as $input => $expectedResult){
            $result = $this->nameManipulator->toCamelUpperCaseFirst($input);
            $this->assertSame($expectedResult, $result);
        }
    }

    public function testToCamelLowerCaseFirst()
    {
        $cases = [
            "MyClassName" => "myClassName",
            "myClassName" => "myClassName",
            "Class" => "class",
            "class" => "class",
            "URLShortener" => "uRLShortener",
            "myURLShortener" => "myURLShortener",
            "my_e_d_p_thing" => "myEDPThing",
            "some_name" => "someName",
            "some_name_here" => "someNameHere"
        ];
        foreach($cases as $input => $expectedResult){
            $result = $this->nameManipulator->toCamelLowerCaseFirst($input);
            $this->assertSame($expectedResult, $result);
        }
    }

    public function testToUnderscored()
    {
        $cases = [
            "MyClassName" => "my_class_name",
            "myClassName" => "my_class_name",
            "Class" => "class",
            "class" => "class",
            "URLShortener" => "u_r_l_shortener",
            "myURLShortener" => "my_u_r_l_shortener",
            "my_e_d_p_thing" => "my_e_d_p_thing",
            "some_name" => "some_name",
            "some_name_here" => "some_name_here"
        ];
        foreach($cases as $input => $expectedResult){
            $result = $this->nameManipulator->toUnderscored($input);
            $this->assertSame($expectedResult, $result);
        }
    }

    public function testIsCamelUpperCaseFirst()
    {
        $cases = [
            "MyClassName" => true,
            "myClassName" => false,
            "Class" => true,
            "class" => false,
            "URLShortener" => true,
            "myURLShortener" => false,
            "my_e_d_p_thing" => false,
            "some_name" => false,
            "Some_name" => false,
            "some_name_Here" => false
        ];
        foreach($cases as $input => $expectedResult){
            $result = $this->nameManipulator->isCamelUpperCaseFirst($input);
            $this->assertEquals($expectedResult, $result);
        }
    }

    public function testIsCamelLowerCaseFirst()
    {
        $cases = [
            "MyClassName" => false,
            "myClassName" => true,
            "Class" => false,
            "class" => true,
            "URLShortener" => false,
            "myURLShortener" => true,
            "my_e_d_p_thing" => false,
            "some_name" => false,
            "Some_name" => false,
            "some_name_Here" => false
        ];
        foreach($cases as $input => $expectedResult){
            $result = $this->nameManipulator->isCamelLowerCaseFirst($input);
            $this->assertEquals($expectedResult, $result);
        }
    }

    public function testIsUnderscored()
    {
        $cases = [
            "MyClassName" => false,
            "myClassName" => false,
            "Class" => false,
            "class" => true,
            "URLShortener" => false,
            "myURLShortener" => false,
            "my_e_d_p_thing" => true,
            "some_name" => true,
            "Some_name" => false,
            "some_name_Here" => false
        ];
        foreach($cases as $input => $expectedResult){
            $result = $this->nameManipulator->isUnderscored($input);
            $this->assertEquals($expectedResult, $result);
        }
    }

    public function testFailByInvalidName1()
    {
        $name = "_something";
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->nameManipulator->toCamelLowerCaseFirst($name);
    }

    public function testFailByInvalidName2()
    {
        $name = "Something_";
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->nameManipulator->toCamelLowerCaseFirst($name);
    }

    public function testFailByInvalidName3()
    {
        $name = "Name here";
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->nameManipulator->toCamelLowerCaseFirst($name);
    }

    public function testFailByInvalidName4()
    {
        $name = "SomeOtherName!";
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->nameManipulator->toCamelLowerCaseFirst($name);
    }

    public function testFailByInvalidName5()
    {
        $name = "_what_is_this";
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->nameManipulator->toCamelLowerCaseFirst($name);
    }

    public function testFailByInvalidName6()
    {
        $name = "something__here";
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\InvalidArgumentException");
        $this->nameManipulator->toCamelLowerCaseFirst($name);
    }
}
 