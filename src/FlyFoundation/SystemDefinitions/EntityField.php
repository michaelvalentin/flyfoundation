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
     * @return string
     */
    public function getDatabaseName();

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
    const STRING = 'string';
    const TEXT = 'text';
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const BOOLEAN = 'boolean';
    const DATETIME = 'datetime';
}