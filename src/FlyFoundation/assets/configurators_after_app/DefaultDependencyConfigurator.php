<?php


use FlyFoundation\Core\Config;

class DefaultDependencyConfigurator implements \FlyFoundation\Core\Configurator{

    /**
     * @param Config $config
     * @return Config
     * @throws Exception
     * @throws PDOException
     */
    public function apply(Config $config)
    {

        // MySqlDatabase
        $dbHost = $config->get('mysql_database_host');
        $dbUser = $config->get('mysql_database_user');
        $dbPass = $config->get('mysql_database_password');
        $dbName = $config->get('mysql_database_name');
        try{
            $pdo = new PDO('mysql:dbname='.$dbName.';host='.$dbHost,$dbUser,$dbPass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch(PDOException $e){
            // TODO: Exception handling (perhaps it should try again...)
            throw $e;
        }

        $config->dependencies->putDependency("FlyFoundation\\Dependencies\\MySqlDatabase",$pdo,true);
        // EOF MySqlDatabase

        return $config;
    }
}