
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unique(
                'transaction_id',
                'payments_transaction_id_unique'
            );
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->unique(
                'refund_refrence',
                'refunds_refund_refrence_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('payments_transaction_id_unique');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropUnique('refunds_refund_refrence_unique');
        });
    }
};
