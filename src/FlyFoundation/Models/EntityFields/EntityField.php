<?php


namespace FlyFoundation\Models\EntityFields;


interface EntityField {
    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param mixed $data
     */
    public function setValue($data);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $data
     * @return bool
     */
    public function acceptsValue($data);
} 