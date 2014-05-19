<?php


class TestAppSettings implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {
        $testDatabase = json_decode(file_get_contents(__DIR__."/../../mysql-test-database.json"),true);

        $config->set("app_name","TestApp");
        $config->setMany([
            "database_host" => $testDatabase["host"],
            "database_user" => $testDatabase["user"],
            "database_password" => $testDatabase["password"],
            "database_name" => $testDatabase["database"],
            "database_data_object_prefix" => "MySql"
        ]);
        return $config;
    }
}