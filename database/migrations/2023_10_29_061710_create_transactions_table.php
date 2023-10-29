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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('tr_id');
            $table->dateTime('tr_createdt')->default(now());
            $table->uuid('tr_entity');
            $table->foreign('tr_entity')->references('le_id')->on('legal_entities')->cascadeOnDelete();
            $table->uuid('tr_transaction_id');
            $table->float('tr_amount');
            $table->float('tr_commission');
            $table->string('tr_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
