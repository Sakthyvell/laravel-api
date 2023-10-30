<?php

namespace Tests\Feature\Http\Controllers;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LegalEntityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldReturnSuccessForEntityList()
    {
        $this->get('api/entity')
            ->assertStatus(200)
            ->assertJsonIsArray();
    }

    public function testShouldThrowErrorIfInvalidEntityIdPassed()
    {
        $this->get('api/entity/1')
            ->assertStatus(404)
            ->assertJsonStructure(['Error']);
    }

    public function testShouldReturnSuccessIfValidEntityIdPassed()
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
}