<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\SystemDefinitions;


class EmptyEntityDefinition extends AbstractEntityDefinition {

    private $fields;
    private $relations;
    private $validations;
    private $indexes;

    public function __construct()
    {

    }

    /**
     * @return EntityField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param EntityField $entityField
     *
     * @return void
     */
    public function addField(EntityField $entityField)
    {
        $this->fields[] = $entityField;
    }

    /**
     * @return EntityRelation[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param EntityRelation $entityRelation
     *
     * @return void
     */
    public function addRelation(EntityRelation $entityRelation)
    {
        $this->relations[] = $entityRelation;
    }

    /**
     * @return EntityValidation[]
     */
    public function getValidations()
    {
        return $this->validations;
    }

    /**
     * @param EntityValidation $entityValidation
     *
     * @return void
     */
    public function addValidation(EntityValidation $entityValidation)
    {
        $this->validations[] = $entityValidation;
    }

    /**
     * @return EntityIndex[]
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @param EntityIndex $entityIndex
     *
     * @return void
     */
    public function addIndex(EntityIndex $entityIndex)
    {
        $this->indexes[] = $entityIndex;
    }
}