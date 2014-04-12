<?php


namespace FlyFoundation\SystemDefinitions;


interface EntityDefinition
{
    public function __construct();

    /**
     * @return string
     */
    public function getTableName();

    /**
     * @param string $tableName
     * @return void
     */
    public function setTableName($tableName);

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return EntityField[]
     */
    public function getFields();

    /**
     * @param EntityField $entityField
     * @return void
     */
    public function addField(EntityField $entityField);

    /**
     * @return EntityRelation[]
     */
    public function getRelations();

    /**
     * @param EntityRelation $entityRelation
     * @return void
     */
    public function addRelation(EntityRelation $entityRelation);

    /**
     * @return EntityValidation[]
     */
    public function getValidations();

    /**
     * @param EntityValidation $entityValidation
     * @return void
     */
    public function addValidation(EntityValidation $entityValidation);

    /**
     * @return EntityIndex[]
     */
    public function getIndexes();

    /**
     * @param EntityIndex $entityIndex
     * @return void
     */
    public function addIndex(EntityIndex $entityIndex);

    /**
     * @return array
     */
    public function getPrimaryKey();
} 