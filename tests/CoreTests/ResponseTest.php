<?php

use FlyFoundation\App;
use FlyFoundation\Core\Context;
use FlyFoundation\Factory;

require_once __DIR__ . '/../test-init.php';


class StandardResponseTest extends PHPUnit_Framework_TestCase {
    public function testComposeHtml(){
        /** @var \FlyFoundation\Core\StandardResponse $response */
        Factory::setConfig(new \FlyFoundation\Config());
        $response = Factory::load("\\FlyFoundation\\Core\\StandardResponse");
        $response->setDataValue("demo","demo");
        $response->setDataValue("test","test-data");
        $response->setContent("This is a <b>demo</b>");
        $response->wrapInTemplate("<div>{{{content}}}</div>");
        $response->wrapInTemplate("<p>{{demo}}</p><p>{{test}}</p>{{{content}}}");
        $result = $response->composeHtml();
        $expected = "<p>demo</p><p>test-data</p><div>This is a <b>demo</b></div>";
        $this->assertSame($expected,$result);
    }

    //TODO: Could be tested more thoroughly...
}
 