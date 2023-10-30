<?php

namespace App\Component\EntityManager;

use App\Component\EntityManager\DataHandler;
use App\Component\EntityManager\EntityData;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EntityManager{

    /** @var DB */
    protected $dbHelper;

    /** @var DataHandler */
    public $dataHandler;

    /** @var EntityData */
    protected $entity;

    public function __construct(EntityData $entity = null, DB $dbHelper = null)
    {
        $this->dbHelper = $dbHelper ?? new DB();
        $this->entity = $entity ?? new EntityData();
        $this->dataHandler = new DataHandler($this->dbHelper);
    }

    public function getDataHandler(): DataHandler
    {
        return $this->dataHandler;
    }

    public function getEntity() : EntityData
    {
        $entity = $this->dataHandler->getEntityInformation($this->entity->id);
        if(empty($entity)) throw new Exception("Invalid Entity", 404);
        $this->entity->tax_number = $entity[0]->le_id;
        $this->entity->name = $entity[0]->le_name;
        $this->entity->address = $entity[0]->le_address;
        $this->entity->balance = $entity[0]->le_balance;
        return $this->entity;
    } 

    public function getEntityList() : array
    {
        return Cache::remember('entity-list', 60*60*24, function(){
           return $this->dataHandler->getEntityList();
        });
    }
}