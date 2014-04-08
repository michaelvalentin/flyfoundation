<?php


namespace FlyFoundation\Database;


interface DataFinder {
    public function __construct(EntityDefinition $entityDefinition);

    /**
     * @return Entity[]
     */
    public function fetch();

    /**
     * @return DataFinder
     */
    public function findDefault();

    /**
     * @param QueryType $queryType
     * @return DataFinder
     */
    public function find(QueryType $queryType);

    /**
     * @param string $column
     * @param string $value
     * @return DataFinder
     */
    public function whereEquals($column, $value);

    /**
     * @param QueryCondition $queryCondition
     * @return DataFinder
     */
    public function where(QueryCondition $queryCondition);

    /**
     * @param int $pageNumber
     * @return DataFinder
     */
    public function page($pageNumber);

    /**
     * @param int $itemsOnEachPage
     * @return DataFinder
     */
    public function itemsOnEachPage($itemsOnEachPage);

    /**
     * @param string $column
     * @return DataFinder
     */
    public function sortedByColumn($column);

    /**
     * @param QuerySorting $querySorting
     * @return DataFinder
     */
    public function sortedBy(QuerySorting $querySorting);
} 