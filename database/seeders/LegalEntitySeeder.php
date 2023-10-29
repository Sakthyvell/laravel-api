<?php

namespace Database\Seeders;

use App\Models\LegalEntity;
use Illuminate\Database\Seeder;

class LegalEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(): void
    {
        LegalEntity::factory()
            ->count(100)
            ->create();
    }
}
