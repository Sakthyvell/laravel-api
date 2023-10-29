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
        return $this->dbHelper::select("select tq_id from transaction_queue where tq_entity = :id", array(
            'id' => $entityId,
         ));
    }

    public function createTransactionKey(string $entityId)
    {
        return TransactionQueue::create([
            'tq_entity' => $entityId,
        ]);
    }

    public function getEntity(string $key){
        return $this->dbHelper::select("select legal_entities.* from transaction_queue join legal_entities on tq_entity = le_id where tq_id = :id", [
            'id' => $key,
        ]);
    }

    public function getTransactionEntity(string $key){
        return $this->dbHelper::select("select legal_entities.* from transaction_queue join legal_entities on tq_entity = le_id where tq_id = :key", array(
            'key' => $key,
         ));
    }

    public function generateTransactionRecord(TransactionData $transaction)
    {
        $this->dbHelper::statement("insert into transactions (tr_entity, tr_transaction_id, tr_amount, tr_type, tr_commission) values (:entity, :trans_id, :amount, :type, :commission)", [
            'entity' => $transaction->entityId,
            'trans_id' => $transaction->transactionId,
            'amount' => $transaction->amount,
            'type' => $transaction->action,
            'commission' => $transaction->amount * 0.05,
        ]);
    }

    public function deleteTransactionKey(string $transactionKey)
    {
        $this->dbHelper::statement("delete from transaction_queue where tq_id = :tq_id", [
            "tq_id" => $transactionKey,
        ]);
    }

    public function updateEntityBalance(string $entityId, float $balance)
    {
        $this->dbHelper::statement("update legal_entities set le_balance = :balance where le_id = :entityId", [
            "balance" => $balance,
            "entityId" => $entityId,
        ]);
    }
}