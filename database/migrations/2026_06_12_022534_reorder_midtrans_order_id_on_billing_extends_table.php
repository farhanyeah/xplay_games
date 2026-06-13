<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE billing_extends
            MODIFY midtrans_order_id VARCHAR(255) NULL AFTER midtrans_token
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE billing_extends
            MODIFY midtrans_order_id VARCHAR(255) NULL AFTER status_bayar
        ");
    }
};