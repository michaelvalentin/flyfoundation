<?php
require_once __DIR__ . '/../test-init.php';

use FlyFoundation\Database\MySqlCondition;
class MySqlConditionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MySqlCondition
     */
    private $condition;

    protected function setUp()
    {
        parent::setUp();

        $this->condition = new MySqlCondition('test');
    }

    public function testInvert()
    {
        $this->condition->invert();
        $this->condition->equal(3);
        $this->assertSame('`test` != ?', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` == ?', $this->condition->getString());
    }

    public function testEqual()
    {
        $this->condition->equal(3);
        $this->assertSame(array(3), $this->condition->getValues());
        $this->assertSame('`test` == ?', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` != ?', $this->condition->getString());
    }

    public function testLessThan()
    {
        $this->condition->lessThan(3);
        $this->assertSame(array(3), $this->condition->getValues());
        $this->assertSame('`test` < ?', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` > ?', $this->condition->getString());
    }

    public function testGreaterThan()
    {
        $this->condition->greaterThan(3);
        $this->assertSame(array(3), $this->condition->getValues());
        $this->assertSame('`test` > ?', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` < ?', $this->condition->getString());
    }

    public function testLessThanOrEqual()
    {
        $this->condition->lessThanOrEqual(3);
        $this->assertSame(array(3), $this->condition->getValues());
        $this->assertSame('`test` <= ?', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` >= ?', $this->condition->getString());
    }

    public function testGreaterThanOrEqual()
    {
        $this->condition->greaterThanOrEqual(3);
        $this->assertSame(array(3), $this->condition->getValues());
        $this->assertSame('`test` >= ?', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` <= ?', $this->condition->getString());
    }

    public function testIsLike()
    {
        $this->condition->isLike('test');
        $this->assertSame(array('test'), $this->condition->getValues());
        $this->assertSame('`test` LIKE ?', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` NOT LIKE ?', $this->condition->getString());
    }

    public function testIsNull()
    {
        $this->condition->isNull();
        $this->assertSame(array(), $this->condition->getValues());
        $this->assertSame('`test` IS NULL', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` IS NOT NULL', $this->condition->getString());
    }

    public function testIsTrue()
    {
        $this->condition->isTrue();
        $this->assertSame(array(), $this->condition->getValues());
        $this->assertSame('`test` IS TRUE', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` IS NOT TRUE', $this->condition->getString());
    }

    public function testIsIn()
    {
        $this->condition->isIn(array(3,2,1));
        $this->assertSame(array(3,2,1), $this->condition->getValues());
        $this->assertSame('`test` IN(?, ?, ?)', $this->condition->getString());
        $this->condition->invert();
        $this->assertSame('`test` NOT IN(?, ?, ?)', $this->condition->getString());
    }

} 