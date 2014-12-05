<?php


use FlyFoundation\Core\Config;

class DefaultDatabaseConfigurator implements \FlyFoundation\Core\Configurator{

    public function apply(Config $config)
    {
        $config->setMany([
            "mysql_database_host" => "127.0.0.1",
            "mysql_database_user" => "root",
            "mysql_database_password" => "1234",
            "mysql_database_name" => "flyfoundation_test"
        ]);
        return $config;
    }
}