<?php


class DefaultDatabaseConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
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