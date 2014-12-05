<?php


use FlyFoundation\Core\Config;

class TestAppSettings implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     */
    public function apply(Config $config)
    {
        $testDatabase = json_decode(file_get_contents(__DIR__."/../../mysql-test-database.json"),true);

        $config->set("app_name","TestApp");
        $config->setMany([
            "mysql_database_host" => $testDatabase["host"],
            "mysql_database_user" => $testDatabase["user"],
            "mysql_database_password" => $testDatabase["password"],
            "mysql_database_name" => $testDatabase["database"],
        ]);
        return $config;
    }
}