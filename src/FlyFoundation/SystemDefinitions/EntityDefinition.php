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

    /**
     * @return array
     */
    public function getPersistentFields()
    {
        $this->requireFinalized();
        if(!is_array($this->fields)){
            return [];
        }
        $result = [];
        foreach($this->fields as $field){
            if($field instanceof PersistentField){
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getRelationFields()
    {
        $this->requireFinalized();
        if(!is_array($this->fields)){
            return [];
        }
        $result = [];
        foreach($this->fields as $field){
            if($field instanceof RelationField){
                $result[] = $field;
            }
        }
        return $result;
    }

    public function getCalculatedFields()
    {
        if(!is_array($this->fields)){
            return [];
        }
        $result = [];
        foreach($this->fields as $field){
            if($field instanceof CalculatedField){
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

    public function finalize()
    {
        if(!is_array($this->fields) || count($this->fields)<1){
            throw new InvalidArgumentException("An entity definition must have at least one field.");
        }
        parent::finalize();
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
        foreach($validationsData as $validationData)
        {
            $validation = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\EntityValidation");
            $validation->applyOptions($validationData);
            $validation->setEntityDefinition($this);
            $this->validations[] = $validation;
        }
    }

    protected function applyIndexes(array $indexesData)
    {
        foreach($indexesData as $indexData)
        {
            $index = $this->getFactory()->load("\\FlyFoundation\\SystemDefinitions\\EntityIndex");
            $index->applyOptions($indexData);
            $index->setEntityDefinition($this);
            $this->validations[] = $index;
        }
    }
}