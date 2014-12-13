<?php


namespace FlyFoundation\Database;

use FlyFoundation\Dependencies\AppConfig;
use FlyFoundation\Core\Dependant;
use FlyFoundation\Dependencies\MySqlDatabase;
use FlyFoundation\Exceptions\InvalidArgumentException;
use PDO;
use PDOException;

class JsonGenericDataStore extends GenericDataStore implements Dependant{

    /**
     * @param array $data
     * @return mixed
     */
    public function createEntry(array $data)
    {
        // TODO: Implement createEntry() method.
    }

    /**
     * @param array $identity
     * @return array
     */
    public function readEntry(array $identity)
    {
        // TODO: Implement readEntry() method.
    }

    /**
     * @param array $data
     * @param array $id
     * @return void
     */
    public function updateEntry(array $data)
    {
        // TODO: Implement updateEntry() method.
    }

    /**
     * @param array $id
     * @return void
     */
    public function deleteEntry(array $id)
    {
        // TODO: Implement deleteEntry() method.
    }

    /**
     * @param array $identity
     * @return bool
     */
    public function containsEntry(array $identity)
    {
        // TODO: Implement containsEntry() method.
    }

    /**
     * @return void
     */
    public function onDependenciesLoaded()
    {
        // TODO: Implement onDependenciesLoaded() method.
    }

    /**
     * @return void
     */
    public function afterConfiguration()
    {
        // TODO: Implement afterConfiguration() method.
    }

    public function fetchEntries(array $conditions = [])
    {
        // TODO: Implement fetchEntries() method.
    }
}