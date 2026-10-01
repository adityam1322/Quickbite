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
        Schema::create('restaurant_staff', function (Blueprint $table) {
    $table->id();

    $table->foreignId('restaurant_id')
        ->constrained('restaurants')
        ->cascadeOnDelete();

    $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->string('role');

    $table->boolean('is_active')->default(true);

    $table->timestamps();

    $table->unique(['restaurant_id', 'user_id']);
    $table->index(['restaurant_id', 'is_active']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_staff');
    }
};
