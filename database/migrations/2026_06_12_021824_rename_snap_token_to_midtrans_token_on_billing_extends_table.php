<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billing_extends', function (Blueprint $table) {
            $table->renameColumn('snap_token', 'midtrans_token');
        });
    }

    public function down(): void
    {
        Schema::table('billing_extends', function (Blueprint $table) {
            $table->renameColumn('midtrans_token', 'snap_token');
        });
    }
};