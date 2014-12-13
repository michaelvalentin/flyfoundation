<?php


namespace FlyFoundation\Controllers;

use FlyFoundation\Core\Response;
use FlyFoundation\Database\DataFinder;
use FlyFoundation\Database\DataMapper;
use FlyFoundation\Exceptions\InvalidOperationException;
use FlyFoundation\Factory;
use FlyFoundation\Forms\Form;
use FlyFoundation\Models\Entity;
use FlyFoundation\Models\Model;
use FlyFoundation\Views\View;

class GenericEntityController extends AbstractController{

    /** @var string */
    private $entityName;
    /** @var array */
    private $identityFieldSets = [];
    /** @var Form */
    private $createForm;
    /** @var Form */
    private $updateForm;

    public function read(array $arguments = [])
    {

    }

    public function readRespondsTo(array $arguments = [])
    {
        $id = $this->findExistingIdentityInArguments();
        if($id){
            return true;
        }else{
            return false;
        }
    }

    private function findExistingIdentityInArguments(array $arguments = [])
    {
        foreach($this->getIdentityFieldSets() as $identityFields){
            $identity = [];
            foreach($identityFields as $identityField){
                if(isset($arguments[$identityField]) && $arguments[$identityField]){
                    $identity[$identityField] = $arguments[$identityField];
                }
            }
            if(count($identity) == count($identityFields)){
                if($this->getDataMapper()->load($identity)){
                    return true;
                }
            }
        }
        return false;
    }

    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    public function addIdentityFieldSet(array $identity)
    {
        $this->identityFieldSets[] = $identity;
    }

    public function setCreateForm(Form $createForm)
    {
        $this->createForm = $createForm;
    }

    public function setUpdateForm(Form $updateForm)
    {
        $this->updateForm = $updateForm;
    }

    public function getEntityName()
    {
        if(!$this->entityName){
            throw new InvalidOperationException(
                "Entity name must be set before generic entity controller can be used."
            );
        }
        return $this->entityName;
    }

    /**
     * @return array
     */
    protected function getIdentityFieldSets()
    {
        return $this->identityFieldSets;
    }

    protected function getCreateForm()
    {
        if(!$this->createForm){
            $this->createForm = Factory::loadForm($this->getEntityName(), "Create");
        }
        return $this->createForm;
    }

    protected function getUpdateForm()
    {
        if(!$this->updateForm){
            $this->updateForm = Factory::loadForm($this->getEntityName(), "Update");
        }
        return $this->updateForm;
    }
} 