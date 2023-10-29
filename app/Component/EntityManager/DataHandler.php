<?php

namespace App\Component\EntityManager;

use Illuminate\Support\Facades\DB;

class DataHandler{

    public $dbHelper;

    public function __construct(DB $dbHelper = null)
    {
        $this->dbHelper = $dbHelper ?? new DB();
    }

    public function getEntityInformation(string $entityId): array
    {
        return $this->dbHelper::select('select * from legal_entities where le_id = :id', array(
            'id' => $entityId
        ));
    }

    public function getEntityList(): array
    {
        return $this->dbHelper::select('select le_id, le_name from legal_entities');
    }
}