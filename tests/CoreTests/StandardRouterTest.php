<?php

use FlyFoundation\Core\StandardRouter;

require_once __DIR__ . '/../test-init.php';


class StandardRouterTest extends PHPUnit_Framework_TestCase {
    /** @var  StandardRouter */
    private $router;

    protected function setUp()
    {
        $app = new \FlyFoundation\App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $context = new \FlyFoundation\Core\Context();
        $this->router = $app->getFactory($context)->load("\\FlyFoundation\\Core\\StandardRouter");
        parent::setUp();
    }

    public function testMatchUriPattern()
    {
        $uriPattern = "GET:demo/{alias}/author-{name}";
        $query = "GET:demo/foo/author-bar";

        $result = $this->router->matchUri($uriPattern, $query);

        $this->assertTrue($result[0]);
        $this->assertSame("foo",$result[1]["alias"]);
        $this->assertSame("bar",$result[1]["name"]);
    }

    public function testMatchUriPatternNonMatchingQuery()
    {
        $uriPattern = "GET:demo/{alias}/author-{name}";
        $query = "GET:demo/foo/author2-bar";

        $result = $this->router->matchUri($uriPattern, $query);

        $this->assertFalse($result[0]);
    }

    public function testMatchUriPatternWithPrefix()
    {
        $uriPattern = "GET:demo/{alias}/author-{name}";
        $query = "GET:some/demo/foo/author-bar";

        $result = $this->router->matchUri($uriPattern, $query);

        $this->assertFalse($result[0]);
    }

    public function testMatchUriPatternWithPostfix()
    {
        $uriPattern = "GET:demo/{alias}/author-{name}";
        $query = "GET:demo/foo/freak/author-bar/test";

        $result = $this->router->matchUri($uriPattern, $query);

        $this->assertTrue($result[0]);
        $this->assertSame("foo/freak",$result[1]["alias"]);
        $this->assertSame("bar/test",$result[1]["name"]);
    }

    public function testGetSystemQuery1(){
        $router = $this->router;
        $router->setContext(new \FlyFoundation\Core\Context([
            "httpVerb" => "GET"
        ]));
        $systemQuery = $router->getSystemQuery("mytest/some-alias-here");

        $this->assertInstanceOf("\\TestApp\\Controllers\\TestAppSpecialController",$systemQuery->getController());
        $this->assertSame("show",$systemQuery->getMethod());
        $this->assertSame("some-alias-here",$systemQuery->getArguments()["alias"]);
    }

    public function testGetSystemQuery2(){
        $router = $this->router;
        $router->setContext(new \FlyFoundation\Core\Context([
            "httpVerb" => "POST"
        ]));
        $systemQuery = $router->getSystemQuery("mytest/delete/41");

        $this->assertInstanceOf("\\TestApp\\Controllers\\TestAppSpecialController",$systemQuery->getController());
        $this->assertSame("delete",$systemQuery->getMethod());
        $this->assertSame("41",$systemQuery->getArguments()["id"]);
    }

    public function testGetSystemQuery3(){
        $router = $this->router;
        $router->setContext(new \FlyFoundation\Core\Context([
            "httpVerb" => "GET"
        ]));
        $systemQuery = $router->getSystemQuery("something-else-here");

        $this->assertInstanceOf("\\TestApp\\Controllers\\TestAppSpecialController",$systemQuery->getController());
        $this->assertSame("showAll",$systemQuery->getMethod());
        $this->assertSame("something-else-here",$systemQuery->getArguments()["alias"]);
    }

    public function testGetSystemQuery4(){
        $router = $this->router;
        $router->setContext(new \FlyFoundation\Core\Context([
            "httpVerb" => "GET"
        ]));
        $systemQuery = $router->getSystemQuery("");

        $this->assertInstanceOf("\\TestApp\\Controllers\\TestAppSpecialController",$systemQuery->getController());
        $this->assertSame("showFrontPage",$systemQuery->getMethod());
    }
}
 