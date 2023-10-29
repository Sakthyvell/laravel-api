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
        Schema::create('transaction_queue', function (Blueprint $table) {
            $table->uuid('tq_id')->primary();
            $table->dateTime('tq_createdt')->default(now());
            $table->uuid('tq_entity');
            $table->foreign('tq_entity')->references('le_id')->on('legal_entities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_queue');
    }
};
