<?php


namespace FlyFoundation\SystemDefinitions;


class PersistentField extends EntityField{
    private $defaultValue;
    private $isAutoIncremented;

    public function getDefaultValue()
    {
        $this->requireFinalized();
        return $this->defaultValue;
    }

    public function isAutoIncremented()
    {
        $this->requireFinalized();
        return $this->isAutoIncremented;
    }

    protected function applyDefaultValue($defaultValue)
    {
        //TODO: Could use some sort of check...
        $this->defaultValue = $defaultValue;
    }

    protected function applyAutoIncrement($autoIncrement)
    {
        $this->isAutoIncremented = $autoIncrement ? true : false;
    }
} 