<?php


class DefaultDependencyConfigurator implements \FlyFoundation\Configurator{

    /**
     * @param \FlyFoundation\Config $config
     * @return \FlyFoundation\Config
     */
    public function apply(\FlyFoundation\Config $config)
    {

        // MySqlDatabase
        $dbHost = $config->get('db_host', 'localhost');
        $dbUser = $config->get('db_user');
        $dbPass = $config->get('db_pass');
        $dbName = $config->get('db_name');
        try{
            $pdo = new PDO('mysql:dbname='.$dbName.';host='.$dbHost,$dbUser,$dbPass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch(PDOException $e){
            // TODO: Exception handling (perhaps it should try again...)
            throw $e;
        }
        $config->dependencies->putDependency("\\FlyFoundation\\Dependencies\\MySqlDatabase",$pdo,true);
        // EOF MySqlDatabase

        return $config;
    }
}