<?php

use FlyFoundation\App;

require_once __DIR__.'/test-init.php';


class StandardFileLoaderTest extends PHPUnit_Framework_TestCase {
    /** @var  \FlyFoundation\Core\FileLoader $fileLoader */
    private $fileLoader;

    protected function setUp()
    {
        $app = new App();
        $app->addConfigurators(__DIR__."/TestApp/configurators");
        $context = new \FlyFoundation\Core\Context();
        $factory = $app->getFactory($context);
        $this->fileLoader = $factory->load("\\FlyFoundation\\Core\\FileLoader");
    }

    public function testFindSomeFile()
    {
        $result = $this->fileLoader->findFile("readme.md");
        $readme_contents_result = file_get_contents($result);
        $readme_contents = file_get_contents(__DIR__."/TestApp/readme.md");
        $this->assertSame($readme_contents, $readme_contents_result);
    }

    public function testFindingFileThatDoesntExist()
    {
        $this->setExpectedException("\\FlyFoundation\\Exceptions\\NonExistantFileException");
        $result = $this->fileLoader->findFile("file-that/does-not/exist");
    }

    public function testLoadingEntityDefinitionInExtraDir()
    {
        $result = $this->fileLoader->findEntityDefinition("demo");
        $contents_result = file_get_contents($result);
        $contents = file_get_contents(__DIR__."/TestApp/entities/demo.json");
        $this->assertSame($contents, $contents_result);
    }

    public function testLoadingTemplateFile()
    {
        $result = $this->fileLoader->findTemplate("demo");
        $contents_result = file_get_contents($result);
        $contents = file_get_contents(__DIR__."/../src/FlyFoundation/templates/demo.mustache");
        $this->assertSame($contents, $contents_result);
    }

    public function testLoadingFileImplementedInBothFlyFoundationAndTestApp()
    {
        $result = $this->fileLoader->findFile("entity_definitions/readme.md");
        $contents_result = file_get_contents($result);
        $contents = file_get_contents(__DIR__."/TestApp/entity_definitions/readme.md");
        $this->assertSame($contents, $contents_result);
    }
}
 