<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\Field;
use FlyFoundation\Database\Fields\DataField;

interface DataStore
{
    /**
     * @param array $data
     * @return mixed
     */
    public function createEntry(array $data);

    /**
     * @param array $identity
     * @return array
     */
    public function readEntry(array $identity);

    /**
     * @param array $data
     * @param array $id
     * @return void
     */
    public function updateEntry(array $data);

    /**
     * @param array $id
     * @return void
     */
    public function deleteEntry(array $id);

    /**
     * @param array $data
     * @return bool
     */
    public function isValidData(array $data);

    /**
     * @param array $identity
     * @return bool
     */
    public function isValidIdentity(array $identity);

    /**
     * @param array $data
     * @return mixed[]
     */
    public function extractIdentity(array $data);

    /**
     * @param array $identity
     * @return bool
     */
    public function containsEntry(array $identity);

    /**
     * @return string
     */
    public function getEntityName();

    /**
     * @param string $name
     * @return DataField
     */
    public function getField($name);

    /**
     * @return DataField[]
     */
    public function getFields();
}