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
    private $primaryKey;

    /**
     * @param $columnName
     * @param $type
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

    /**
     * @param boolean $boolean
     */
    public function setPrimaryKey($boolean)
    {
        $this->primaryKey = $boolean;
    }

    /**
     * @return boolean
     */
    public function isPrimaryKey()
    {
        return $this->primaryKey;
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