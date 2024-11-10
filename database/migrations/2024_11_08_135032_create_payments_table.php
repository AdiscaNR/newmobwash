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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tx_id');
            $table->unsignedBigInteger('payment_type');
            $table->BigInteger('total');
            $table->string('image')->nullable();
            $table->timestamps();
            
            $table->foreign('tx_id')->references('id')->on('transactions');
            $table->foreign('payment_type')->references('id')->on('payment_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
