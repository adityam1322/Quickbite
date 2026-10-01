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
        Schema::create('restaurant_service_areas', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('restaurant_id')
                ->constrained('restaurants')
                ->cascadeOnDelete();
        
            $table->string('area_name');
            $table->string('city');
            $table->string('postal_code')->nullable();
        
            $table->boolean('is_active')->default(true);
        
            $table->timestamps();
        
            $table->unique(['restaurant_id', 'area_name']);
            $table->index(['city', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_service_areas');
    }
};
