<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\SystemDefinitions;


abstract class AbstractEntityDefinition implements EntityDefinition
{
    private $tableName;

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     *
     * @return void
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        $name = str_replace('_', ' ', $this->tableName);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }
}