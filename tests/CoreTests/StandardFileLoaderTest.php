<?php

use FlyFoundation\App;
use FlyFoundation\Core\Context;
use FlyFoundation\Factory;

require_once __DIR__ . '/../test-init.php';


class StandardFileLoaderTest extends PHPUnit_Framework_TestCase {
    /** @var  \FlyFoundation\Core\FileLoader $fileLoader */
    private $fileLoader;

    protected function setUp()
    {
        $app = new App();
        $app->addConfigurators(TEST_BASE."/TestApp/configurators");
        $app->prepareCoreDependencies(new Context(""));

        $this->fileLoader = Factory::loadWithoutImplementationSearch("\\FlyFoundation\\Core\\StandardFileLoader");
    }

    public function testFindSomeFile()
    {
        $result = $this->fileLoader->findFile(TEST_BASE."/TestApp/readme.md");
        $readme_contents_result = file_get_contents($result);
        $readme_contents = file_get_contents(TEST_BASE."/TestApp/readme.md");
        $this->assertSame($readme_contents, $readme_contents_result);
    }

    public function testFindingFileThatDoesntExist()
    {
        $result = $this->fileLoader->findFile("file-that/does-not/exist");
        $this->assertFalse($result);
    }

    public function testLoadingTemplateFile()
    {
        $result = $this->fileLoader->findTemplate("demo");
        $contents_result = file_get_contents($result);
        $contents = file_get_contents(TEST_BASE."/../src/FlyFoundation/assets/templates/demo.mustache");
        $this->assertSame($contents, $contents_result);
    }

    public function testLoadingFileImplementedInBothFlyFoundationAndTestApp()
    {
        $result = $this->fileLoader->findFile("pages/index.mustache");
        $contents_result = file_get_contents($result);
        $contents = file_get_contents(TEST_BASE."/TestApp/pages/index.mustache");
        $this->assertSame($contents, $contents_result);
    }
}
 