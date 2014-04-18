<?php


class DefaultDatabaseConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $config->setMany([
            "database_host" => "localhost",
            "database_user" => "root",
            "database_password" => "",
            "database_name" => "",
            "database_data_object_prefix" => "MySql"
        ]);
        return $config;
    }
}