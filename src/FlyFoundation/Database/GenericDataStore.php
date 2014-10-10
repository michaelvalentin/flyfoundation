<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\Fields\DataField;
use FlyFoundation\Dependencies\AppConfig;
use PDO;
use PDOException;
use FlyFoundation\Exceptions\InvalidArgumentException;

abstract class GenericDataStore implements DataStore
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var DataField[]
     */
    private $fields = array();

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addField(DataField $field)
    {
        $this->fields[] = $field;
    }

    public function getFields()
    {
        return $this->fields;
    }
} 