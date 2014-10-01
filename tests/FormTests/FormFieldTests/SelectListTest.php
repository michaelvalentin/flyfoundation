<?php

require_once __DIR__ . '/../../test-init.php';

use FlyFoundation\Models\Forms\FormFields\SelectList;

class SelectListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SelectList
     */
    private $selectList;

    /**
     * @var string[]
     */
    private $options;

    protected function setUp()
    {
        parent::setUp();

        $this->selectList = new SelectList();
        $this->selectList->setName('demo');
        $this->selectList->setValue('demo value');
        $this->selectList->addClass('demo-class');

        $this->options = array('demo value', 'demo value 2', 'demo value 3');
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf('\\FlyFoundation\\Models\\Forms\\FormFields\\FormField', $this->selectList);
    }

    public function testFieldHTML()
    {
        $this->selectList->setOptions($this->options);
        $expected = '<select name="demo" class="demo-class"><option selected="selected">demo value</option><option>demo value 2</option><option>demo value 3</option></select>';
        $this->assertSame($expected, $this->selectList->getFieldHTML());
    }
} 