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
            "mysql_database_host" => $testDatabase["host"],
            "mysql_database_user" => $testDatabase["user"],
            "mysql_database_password" => $testDatabase["password"],
            "mysql_database_name" => $testDatabase["database"],
        ]);
        return $config;
    }
}