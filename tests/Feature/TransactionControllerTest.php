<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('legal_entities')
        ->insert([
        'le_id' => '123456789',
        'le_tax_number' => '123456789',
        'le_name' => 'Company',
        'le_address' => '123 Street 456',
        'le_balance' => 12345,
        ]);
    }

    public function testShouldThrowErrorIfNoEntityIdPassed()
    {
        $this->post('api/transaction')
        ->assertStatus(400)
        ->assertJsonStructure(['Error']);
    }

    public function testShouldThrowErrorIfInvalidEntityIdPassed()
    {
        $this->post('api/transaction', ['entity_id' => '123456'])
        ->assertStatus(404)
        ->assertJsonStructure(['Error']);
    }

    public function testShouldReturnSuccessIfValidEntityIdPassed()
    {

        $this->post('api/transaction', ['entity_id' => '123456789'])
        ->assertStatus(200)
        ->assertJsonStructure(['key']);
    }
    
    public function testShouldThrowErrorIfNoIdempotentKeyPassed()
    {
        $this->post('api/transaction', ['action' => 'withdraw', 'amount' => '1234'])
        ->assertStatus(400)
        ->assertJsonStructure(['Error']);
    }

    public function testShouldThrowErrorIfNoActionPassed()
    {
        $response = json_decode($this->post('api/transaction', ['entity_id' => '123456789'])->getContent(), true);

        $this->put('api/transaction', ['amount' => '1234'], ['idempotent-key' => $response['key']])
        ->assertStatus(400)
        ->assertJsonStructure(['Error']);
    }

    public function testShouldThrowErrorIfInvalidActionPassed()
    {
        $response = json_decode($this->post('api/transaction', ['entity_id' => '123456789'])->getContent(), true);

        $this->put('api/transaction', ['action' => 'action', 'amount' => '1234'], ['idempotent-key' => $response['key']])
        ->assertStatus(400)
        ->assertJsonStructure(['Error']);
    }

    public function testShouldThrowErrorIfNoAmountPassed()
    {
        $response = json_decode($this->post('api/transaction', ['entity_id' => '123456789'])->getContent(), true);

        $this->put('api/transaction', ['action' => 'action'], ['idempotent-key' => $response['key']])
        ->assertStatus(400)
        ->assertJsonStructure(['Error']);
    }

    public function testShouldThrowErrorIfInvalidAmountPassed()
    {
        $response = json_decode($this->post('api/transaction', ['entity_id' => '123456789'])->getContent(), true);

        $this->put('api/transaction', ['action' => 'action', 'amount' => 'test1234'], ['idempotent-key' => $response['key']])
        ->assertStatus(400)
        ->assertJsonStructure(['Error']);
    }

    public function testShouldReturnSuccessIfTransactionPerformed()
    {
        $response = json_decode($this->post('api/transaction', ['entity_id' => '123456789'])->getContent(), true);

        $this->put('api/transaction', ['action' => 'deposit', 'amount' => 1234], ['idempotent-key' => $response['key']])
        ->assertStatus(200);
    }

    protected function tearDown(): void
    {
        DB::table('legal_entities')
        ->where('le_id', '=', '123456789')
        ->delete();

        DB::table('transaction_queue')
        ->where('tq_entity', '=', '123456789')
        ->delete();

        DB::table('transactions')
        ->where('tr_entity', '=', '123456789')
        ->delete();

        parent::tearDown();
    }
}
