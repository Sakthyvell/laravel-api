<?php

namespace Tests\Feature\Http\Controllers;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LegalEntityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetEntityListValidResponse()
    {
        $response = $this->get('api/entity');

        $response->assertStatus(200);

        $response->assertJsonIsArray();

    }

    public function testGetEntityValidResponse()
    {
        DB::table('legal_entities')
            ->insert([
            'le_id' => '123456789',
            'le_tax_number' => '123456789',
            'le_name' => 'Company',
            'le_address' => '123 Street 456',
            'le_balance' => 12345,
        ]);

        $response = $this->get("api/entity/123456789");

        $response->assertStatus(200);

        $response->assertJson([
            "id" => '123456789',
            "tax_number" => "123456789",
            "name" => "Company",
            "address" => "123 Street 456",
            "balance" => 12345
        ]);

        DB::table('legal_entities')
            ->where('le_id', '=', '123456789')
            ->delete();
    }

    public function testGetEntityInalidResponse()
    {
        $response = $this->get("api/entity/123");
        $response->assertStatus(404);
    }
}