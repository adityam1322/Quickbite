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
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->unsignedInteger('attempt_number');
            $table->string('transaction_id')->nullable();
            $table->decimal('amount');
            $table->string('status');
            $table->text('responce_massage')->nullable();
            $table->timestamp('attempted-at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_attempts');
    }
};
