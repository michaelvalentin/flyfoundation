<?php


namespace FlyFoundation\Dependencies;

use FlyFoundation\Exceptions\UnsetDependencyException;
use PDO;

trait MySqlDatabase {

    /** @var PDO */
    private $pdo;

    public function setMySqlDatabase(PDO $pdo){
        $this->pdo = $pdo;
    }

    /**
     * @return PDO
     */
    public function getMySqlDatabase(){
        if($this->pdo == null){
            throw new UnsetDependencyException(
                "The class tried to access an unset dependency (MySqlDatabase). Dependencies should be set right after initialization.
            This is easily done with standard dependencies according to the configuration, by loading the dependency via the Factory. If
            this error occurs when loading with the factory, the dependency is not registered in the configuration. In testing and other
            cases where there is valid reason not to use the system default, the dependency can be set via setMySqlDatabase"
            );
        }
        return $this->pdo;
    }
} 