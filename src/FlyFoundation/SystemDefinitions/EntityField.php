<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\SystemDefinitions;

interface EntityField
{
    public function __construct();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     * @return void
     */
    public function getValidations($type);
}

/**
 * Class EntityFieldType
 *
 * Different field types
 *
 * @package FlyFoundation\SystemDefinitions
 */
class EntityFieldType
{
    const String = 'string';
    const Text = 'text';
    const Int = 'int';
    const Float = 'float';
    const Boolean = 'boolean';
    const DateTime = 'datetime';
}