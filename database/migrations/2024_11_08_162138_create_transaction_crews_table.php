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
        Schema::create('transaction_crews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tx_id');
            $table->unsignedBigInteger('crew_id');
            $table->timestamps();

            $table->foreign('tx_id')->references('id')->on('transactions');
            $table->foreign('crew_id')->references('id')->on('crews');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_crews');
    }
};
