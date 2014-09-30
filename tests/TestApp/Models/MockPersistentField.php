<?php


namespace TestApp\Models;


use FlyFoundation\Models\EntityFields\PersistentField;

class MockPersistentField extends PersistentField{

    /**
     * @param mixed $data
     * @return bool
     */
    public function acceptsValue($data)
    {
        // Accepts all values to allow testing all types of objects..
        return true;
    }
}