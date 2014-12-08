<?php


namespace FlyFoundation\Models;


interface Entity {

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $calledFromDb
     * @return string[]
     */
    public function getPersistentData($calledFromDb);

    /**
     * @param array $data
     * @param string $calledFromDb
     * @return void
     */
    public function setPersistentData(array $data, $calledFromDb);

    /**
     * @return bool
     */
    public function validate();

    /**
     * @return string[]
     */
    public function getErrors();
}