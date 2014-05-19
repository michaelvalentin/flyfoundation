<?php


namespace FlyFoundation\SystemDefinitions;


use FlyFoundation\Exceptions\InvalidArgumentException;

class EntityDefinition extends DefinitionComponent{

    /** @var string */
    protected $name;
    /** @var EntityField[] */
    protected $fields;
    /** @var  EntityValidation[] */
    protected $validations;
    /** @var  EntityIndex[] */
    protected $indexes;
    /** @var  SystemDefinition */
    private $systemDefinition;

    /**
     * @param SystemDefinition $systemDefinition
     */
    public function setSystemDefinition(SystemDefinition $systemDefinition)
    {
        $this->systemDefinition = $systemDefinition;
    }

    /**
     * @return SystemDefinition
     */
    public function getSystemDefinition()
    {
        return $this->systemDefinition;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $this->requireFinalized();
        return $this->name;
    }

    /**
     * @return EntityField[]
     */
    public function getFields()
    {
        $this->requireFinalized();
        return $this->fields;
    }

    public function getField($name){
        $this->requireFinalized();
        if(!$this->hasField($name)){
            throw new InvalidArgumentException("The entity definition ".$this->getName()." does not contain a field ".$name);
        }
        return $this->fields[$name];
    }

    public function hasField($name){
        $this->requireFinalized();
        return isset($this->fields[$name]);
    }

    public function getPersistentFields()
    {
        $this->requireFinalized();
        return $this->getFilteredFields(function($field){
            return $field instanceof PersistentField;
        });
    }

    public function getRelationFields()
    {
        $this->requireFinalized();
        return $this->getFilteredFields(function($field){
            return $field instanceof RelationField;
        });
    }

    public function getCalculatedFields()
    {
        $this->requireFinalized();
        return $this->getFilteredFields(function($field){
            return $field instanceof CalculatedField;
        });
    }

    public function getPrimaryKeyFields()
    {
        $this->requireFinalized();
        return $this->getFilteredFields(function($field){
            if(!$field instanceof PersistentField) return false;
            return $field->isInPrimaryKey();
        });
    }

    private function getFilteredFields(callable $check){
        if(!is_array($this->fields)){
            return [];
        }
        $result = [];
        foreach($this->fields as $field)
        {
            if(call_user_func($check, $field)){
                $result[] = $field;
            }

        }
        return $result;
    }

    public function getValidations()
    {
        $this->requireFinalized();
        return $this->validations;
    }

    public function getIndexes()
    {
        $this->requireFinalized();
        return $this->indexes;
    }

    public function validate()
    {
        if(!is_array($this->fields) || count($this->fields)<1){
            throw new InvalidArgumentException("An entity definition must have at least one field.");
        }
        parent::validate();
    }


    protected function applyFields(array $fieldsData)
    {
        foreach($fieldsData as $fieldData)
        {
            //TODO: This should split them into different types of fields!
            $field = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\PersistentField");
            $field->applyOptions($fieldData);
            $field->setEntityDefinition($this);
            if(!isset($fieldData["name"])){
                throw new InvalidArgumentException("A field must have a name to be in an entity definition.");
            }
            $this->fields[$fieldData["name"]] = $field;
        }
    }

    protected function applyName($name)
    {
        $this->name = $name;
    }

    protected function applyValidations(array $validationsData)
    {
        //TODO: Implement
        /*foreach($validationsData as $validationData)
        {
            $validation = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\EntityValidation");
            $validation->applyOptions($validationData);
            $validation->setEntityDefinition($this);
            $this->validations[] = $validation;
        }*/
    }

    protected function applyIndexes(array $indexesData)
    {
        //TODO: Implement
        /*
        foreach($indexesData as $indexData)
        {
            $index = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\EntityIndex");
            $index->applyOptions($indexData);
            $index->setEntityDefinition($this);
            $this->validations[] = $index;
        }
        */
    }
}