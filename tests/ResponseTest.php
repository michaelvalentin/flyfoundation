<?php

use FlyFoundation\App;

require_once __DIR__.'/test-init.php';


class ResponseTest extends PHPUnit_Framework_TestCase {
    public function testComposeHtml(){
        $app = new App();
        $factory = $app->getFactory();
        /** @var \FlyFoundation\Core\StandardResponse $response */
        $response = $factory->load("\\FlyFoundation\\Core\\Response");
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
 