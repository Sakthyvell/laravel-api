<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateTransactionValidResponse()
    {
        DB::table('legal_entities')
            ->insert([
            'le_id' => '123456789',
            'le_tax_number' => '123456789',
            'le_name' => 'Company',
            'le_address' => '123 Street 456',
            'le_balance' => 12345,
        ]);
        $response = $this->post('api/transaction', [
            'entity_id' => '123456789',
        ]);

        $response->assertStatus(200);

        DB::table('legal_entities')
            ->where('le_id', '=', '123456789')
            ->delete();
    }

    public function testCreateTransactionInvalidResponse()
    {
        
        $response = $this->post('api/transaction', [
            'entity_id' => '12345678910',
        ]);

        $response->assertStatus(404);
    }

    public function testUpdateTransactionInvalidResponse()
    {
        $response = $this->put('api/transaction', [
            'action' => 'deposit',
            'amount' => 100,
        ], [
            'idempotent-key' => '123',
        ]);

        $response->assertStatus(404);
    }

    public function testUpdateTransactionValidResponse()
    {
        $key = DB::table('transaction_queue')
            ->insertGetId([
            'tq_uuid' => '123456789-abc',
            'tq_entity_id' => '123456789',
        ]);

        $response = $this->put('api/transaction', [
            'action' => 'deposit',
            'amount' => '100',
        ], [
            'idempotent-key' => $key,
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            "id" => "123456789",
            "tax_number" => "123456789",
            "name" => "Company",
            "address" => "123 Street 456",
            "balance" => 100
        ]);


        DB::table('transaction_queue')
            ->where('tq_entity_id', '=', '123456789')
            ->delete();

        DB::table('transaction')
            ->where('tr_entity_id', '=', '123456789')
            ->delete();
    }
}
