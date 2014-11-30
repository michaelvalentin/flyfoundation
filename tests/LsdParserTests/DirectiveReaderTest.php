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

        print_r($tree);
    }

}
 