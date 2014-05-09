<?php


namespace FlyFoundation\Database;


use FlyFoundation\SystemDefinitions\EntityDefinition;

class MySqlGenericDataFinder implements DataFinder{

    public function __construct(EntityDefinition $entityDefinition)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * @return Entity[]
     */
    public function fetch()
    {
        // TODO: Implement fetch() method.
    }

    /**
     * @param QueryType $queryType | NULL
     * @return DataFinder
     */
    public function find(QueryType $queryType)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param string $column
     * @param string $value
     * @return DataFinder
     */
    public function whereEquals($column, $value)
    {
        // TODO: Implement whereEquals() method.
    }

    /**
     * @param QueryCondition $queryCondition
     * @return DataFinder
     */
    public function where(QueryCondition $queryCondition)
    {
        // TODO: Implement where() method.
    }

    /**
     * @param int $pageNumber
     * @return DataFinder
     */
    public function page($pageNumber)
    {
        // TODO: Implement page() method.
    }

    /**
     * @param int $itemsOnEachPage
     * @return DataFinder
     */
    public function itemsOnEachPage($itemsOnEachPage)
    {
        // TODO: Implement itemsOnEachPage() method.
    }

    /**
     * @param string $column
     * @return DataFinder
     */
    public function sortedByColumn($column)
    {
        // TODO: Implement sortedByColumn() method.
    }

    /**
     * @param QuerySorting $querySorting
     * @return DataFinder
     */
    public function sortedBy(QuerySorting $querySorting)
    {
        // TODO: Implement sortedBy() method.
    }
}