<?php
/**
 * User: njm1992
 * Date: 09/04/14
 */

namespace FlyFoundation\SystemDefinitions;


class EmptyEntityDefinition extends AbstractEntityDefinition {

    /** @var EntityField[] $fields */
    private $fields;

    /** @var EntityRelation[] $relations */
    private $relations;

    /** @var EntityValidation[] $validations */
    private $validations;

    /** @var EntityIndex[] $indexes */
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

    /**
     * @return array
     */
    public function getPrimaryColumnTypePairs()
    {
        $primaryKey = [];
        foreach($this->fields as $field){
            if($field->isPrimaryKey()){
                $primaryKey[$field->getColumnName()] = $field->getType();
            }
        }
        return $primaryKey;
    }

    public function matchPrimaryKey(array $columnValuePairs)
    {
        $primaryKey = $this->getPrimaryColumnTypePairs();
        foreach($primaryKey as $primaryColumn => $primaryType){
            $foundKey = false;
            foreach($columnValuePairs as $column => $value){
                if($column === $primaryColumn && gettype($value) === $primaryType){
                    $foundKey = true;
                    break;
                }
            }
            if(!$foundKey) return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getColumnTypePairs()
    {
        $fields = [];
        foreach($this->fields as $field){
            $fields[$field->getColumnName()] = $field->getType();
        }
        return $fields;
    }

    /**
     * @param array $columnValuePairs
     * @return bool
     */
    public function matchColumns(array $columnValuePairs)
    {
        $definedColumns = $this->getColumnTypePairs();
        foreach($definedColumns as $definedColumn => $definedType){
            $foundColumn = false;
            foreach($columnValuePairs as $column => $value){
                if($column === $definedColumn && gettype($value) === $definedType){
                    $foundColumn = true;
                    break;
                }
            }
            if(!$foundColumn) return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getPrimaryColumns()
    {
        $primaryColumns = [];
        foreach($this->getFields() as $field){
            if($field->isPrimaryKey()){
                $primaryColumns[] = $field->getColumnName();
            }
        }
        return $primaryColumns;
    }
}