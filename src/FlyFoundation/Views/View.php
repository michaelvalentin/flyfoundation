<?php


namespace FlyFoundation\Views;


interface View {
    /**
     * @param array $data An array of data to set
     */
    public function setData(array $data);

    /**
     * @return array
     */
    public function getData();

    /**
     * @param string $key
     * @param object $value
     */
    public function setValue($key, $value);

    /**
     * @param string $key
     * @return object
     */
    public function getValue($key);

    /**
     * @return array
     */
    public function output();
} 