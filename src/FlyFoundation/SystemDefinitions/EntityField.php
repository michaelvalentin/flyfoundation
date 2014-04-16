<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\SystemDefinitions;

class EntityField
{
    private $columnName;
    private $type;

    /**
     * @param string $columnName
     * @param string $type
     */
    public function __construct($columnName, $type)
    {
        $this->columnName = $columnName;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        $name = str_replace('_', ' ', $this->columnName);
        $name = ucwords($name);
        $name = lcfirst($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
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