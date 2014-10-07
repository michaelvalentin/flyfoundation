<?php

namespace FlyFoundation\Database;

use FlyFoundation\Database\Field;
interface Table
{
    /**
     * @param array $data
     * @return int
     */
    public function createRow(array $data);

    /**
     * @param $id
     * @return array
     */
    public function readRow($id);

    /**
     * @param array $data
     * @param int $id
     * @return void
     */
    public function updateRow(array $data, $id);

    /**
     * @param int $id
     * @return void
     */
    public function deleteRow($id);
}