<?php


namespace FlyFoundation\Models;


interface Entity {
    public function __construct(array $data = []);

    /**
     * @param string $calledFromDb
     * @return string[]
     */
    public function getPersistentData($calledFromDb);

    /**
     * @return string[]
     */
    public function getPrimaryKeyNames();

    /**
     * @return bool
     */
    public function validate();

    /**
     * @return string[]
     */
    public function getErrors();
}