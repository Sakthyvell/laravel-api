<?php

namespace App\Component\TransactionProcessor;

use App\Models\TransactionQueue;
use Illuminate\Support\Facades\DB;

class DataHandler{

    protected $dbHelper;

    public function __construct(DB $dbHelper = null)
    {
        $this->dbHelper = $dbHelper ?? new DB();
    }

    public function getEntityTransaction(string $entityId): array
    {
        return $this->dbHelper::select("select tq_uuid from transaction_queue where tq_entity_id = :id", array(
            'id' => $entityId,
         ));
    }

    public function createTransactionKey(string $entityId)
    {
        return TransactionQueue::create([
            'tq_entity_id' => $entityId,
        ]);
    }

    public function getEntity(string $key){
        return $this->dbHelper::select("select legal_entities.* from transaction_queue join legal_entities on tq_entity_id = le_id where tq_uuid = :id", [
            'id' => $key,
        ]);
    }

    public function getTransactionEntity(string $key){
        return $this->dbHelper::select("select legal_entities.* from transaction_queue join legal_entities on tq_entity_id = le_id where tq_uuid = :key", array(
            'key' => $key,
         ));
    }

    public function generateTransactionRecord(TransactionData $transaction, float $balance)
    {
        DB::table('transactions')
        ->insert([
          'tr_entity_id' => $transaction->entityId,
          'tr_amount' => $transaction->amount,
          'tr_action' => $transaction->action,
          'tr_entity_balance' => $balance,
        ]);
    }

    public function deleteTransactionKey(string $transactionKey)
    {
      DB::table('transaction_queue')
        ->where('tq_uuid', $transactionKey)
        ->delete();
    }

    public function updateEntityBalance(string $entityId, float $balance)
    {
        DB::table('legal_entities')
          ->where('le_id', $entityId)
          ->update([
            'le_balance' => $balance,
          ]);
    }
}