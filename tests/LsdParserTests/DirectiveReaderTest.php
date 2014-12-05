<?php

namespace LsdParserTests;

use FlyFoundation\LsdParser\DirectiveReader;

require_once __DIR__.'/../test-init.php';


class DirectiveReaderTest extends \PHPUnit_Framework_TestCase {

    public function testOnFile1()
    {
        $filePath = __DIR__."/test1.lsd";
        $directiveReader = new DirectiveReader();
        $directiveReader->addFile($filePath);
        $tree = $directiveReader->getDirectiveTree();

        $sampleRes = $tree->getChildNodes()[1]->getLabel();

        $this->assertEquals("AnotherEntity",$sampleRes);
    }

    public function testOnFile2()
    {
        $filePath = __DIR__."/test2.lsd";
        $directiveReader = new DirectiveReader();
        $directiveReader->addFile($filePath);
        $tree = $directiveReader->getDirectiveTree();

        $sampleRes = $tree->getChildNodes()[1]->getChildNodes()[2]->getChildNodes()[0]->getLabel();

        $this->assertEquals("Required",$sampleRes);
    }

    public function testOnFile1AndFile2()
    {
        $filePath1 = __DIR__."/test1.lsd";
        $filePath2 = __DIR__."/test2.lsd";
        $directiveReader = new DirectiveReader();
        $directiveReader->addFile($filePath1);
        $directiveReader->addFile($filePath2);
        $tree = $directiveReader->getDirectiveTree();

        $sampleRes = $tree->getChildNodes()[2]->getChildNodes()[1]->getValue();

        $this->assertEquals("Value",$sampleRes);
    }
}
 