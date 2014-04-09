<?php


namespace FlyFoundation\SystemDefinitions;


interface EntityDefinition
{
    public function __contstruct();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

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
} 