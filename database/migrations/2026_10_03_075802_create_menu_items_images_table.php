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
        Schema::create('menu_items_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_item_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('image_url');
            $table->boolean('is_primary');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items_images');
    }
};
