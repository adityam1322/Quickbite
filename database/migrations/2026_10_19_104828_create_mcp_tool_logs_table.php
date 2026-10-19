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
    Schema::create('mcp_tool_logs', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->string('tool_name');
        $table->unsignedInteger('duration_ms');
        $table->string('outcome');
        $table->timestamps();

        $table->index(['tool_name', 'created_at']);
        $table->index(['user_id', 'created_at']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('mcp_tool_logs');
}
};
