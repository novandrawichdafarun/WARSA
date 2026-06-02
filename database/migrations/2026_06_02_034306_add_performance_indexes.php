<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['warung_id', 'payment_status', 'paid_at'], 'idx_transactions_warung_status_date');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->index('nama_snapshot', 'idx_transaction_items_nama');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index(['warung_id', 'created_at'], 'idx_stock_movements_warung_date');
            $table->index(['warung_id', 'type'], 'idx_stock_movements_warung_type');
        });

        Schema::table('commission_ledger', function (Blueprint $table) {
            $table->index(['warung_id', 'settled_at'], 'idx_commission_warung_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
