<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\Field;
interface DataStore
{
    /**
     * @param array $data
     * @return int
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
}