<?php


namespace FlyFoundation\SystemDefinitions;


class PersistentField extends EntityField{
    private $defaultValue;
    private $isAutoIncremented;
    private $isInPrimaryKey;

    public function getDefaultValue()
    {
        $this->requireFinalized();
        return $this->defaultValue;
    }

    public function isAutoIncremented()
    {
        $this->requireFinalized();
        return $this->isAutoIncremented ? true : false;
    }

    public function isInPrimaryKey()
    {
        $this->requireFinalized();
        return $this->isInPrimaryKey ? true : false;
    }

    protected function applyPrimaryKey($isPrimaryKey)
    {
        $this->isInPrimaryKey = $isPrimaryKey ? true : false;
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