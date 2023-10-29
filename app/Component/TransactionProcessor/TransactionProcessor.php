<?php

namespace App\Component\TransactionProcessor;

use App\Component\TransactionProcessor\DataHandler;
use App\Component\EntityManager\EntityData;
use Exception;
use Illuminate\Support\Facades\DB;

const COMMISSION_PERCENTAGE = 0.05;

class TransactionProcessor{

      /** @var DB */
      protected $dbHelper;

      /** @var DataHandler */
      protected $dataHandler;

      /** @var TransactionData */
      protected $transaction;

      /** @var string */
      protected $key;

      /** @var EntityData */
      protected $entity;

      public function __construct(TransactionData $transaction = null, DB $dbHelper = null, string $key = null)
      {
        $this->dbHelper = $dbHelper ?? new DB();
        $this->transaction = $transaction ?? new TransactionData();
        $this->dataHandler = new DataHandler($this->dbHelper);
        $this->key = $key ?? null;
        $this->load();
      }

      protected function load()
      {
        if(!empty($this->key)) $this->entity = $this->getTransactionEntity();
      }

      protected function getTransactionEntity()
      {
        var_dump($this->key);
        $entityData = $this->dataHandler->getTransactionEntity($this->key);
        if(empty($entityData[0]->le_id)) throw new Exception('Invalid Key', 404);
        $entity = new EntityData();
        $entity->id = $entityData[0]->le_id;
        $entity->tax_number = $entityData[0]->le_tax_number;
        $entity->name = $entityData[0]->le_name;
        $entity->address = $entityData[0]->le_address;
        $entity->balance = $entityData[0]->le_balance;

        return $entity;
      }

      protected function checkExistingTransaction(string $entityId)
      { 
        return $this->dataHandler->getEntityTransaction($entityId);
      }
      
      public function createTransaction() : string
      {
        $currentTransaction = $this->checkExistingTransaction($this->transaction->entityId);
        if(!empty($currentTransaction)) throw new Exception('Ongoing transaction present', 429);
        return $this->dataHandler->createTransactionKey($this->transaction->entityId)->tq_uuid;
      }

      public function completeTransaction()
      {
        $this->performTransaction($this->entity);
        $this->dataHandler->generateTransactionRecord($this->transaction, $this->entity->balance);
        $this->dataHandler->deleteTransactionKey($this->key);

        return $this->entity;
      }

      protected function performTransaction()
      {
        $this->transaction->entityId = $this->entity->id;
        $this->transaction->commission = $this->transaction->amount*COMMISSION_PERCENTAGE;
        
        if($this->transaction->action == TransactionAction::DEPOSIT){
          $newBalance = bcadd($this->entity->balance, $this->transaction->amount, 2);
        }else if($this->transaction->action == TransactionAction::WITHDRAW){
          $newBalance = bcsub($this->entity->balance, $this->transaction->amount, 2);
        }
        
        if($newBalance < 0) throw new Exception('Insufficient Funds', 400);

        $this->dataHandler->updateEntityBalance($this->entity->id, $newBalance);

        $this->entity->balance = $newBalance;
      }
}