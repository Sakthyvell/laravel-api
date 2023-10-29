<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('legal_entities', function (Blueprint $table) {
            $table->uuid('le_id')->primary();
            $table->dateTime('le_createdt')->default(now());
            $table->string('le_tax_number')
                ->unique('index_unique_tax_number')
                ->index();
            $table->string('le_name');
            $table->longText('le_address');
            $table->float('le_balance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entities');
    }
};
