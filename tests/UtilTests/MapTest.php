<?php

require_once __DIR__ . '/test-init.php';

use FlyFoundation\Util\Map;

class MapTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Map
     */
    public $map;

    protected function setUp()
    {
        $this->map = new Map([
            "a" => "a",
            "b" => 2,
            3 => "c",
            "null" => null
        ]);
        parent::setUp();
    }

    public function assertStartupValuesPresent(){
        $this->assertTrue($this->map->get("a") == "a");
        $this->assertTrue($this->map->get("b") == 2);
        $this->assertTrue($this->map->get(3) == "c");
        $this->assertTrue($this->map->get("null") === null);
    }

    public function testInitialization()
    {
        $this->assertStartupValuesPresent();
    }

    public function testPut()
    {
        $map = new Map(["b"=>"test"]);
        $this->map->put("d",$map);
        $res = $this->map->get("d");
        $this->assertEquals($map,$res);
    }

}
 