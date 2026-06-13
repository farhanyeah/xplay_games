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
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('status_booking', ['pending', 'booked', 'done', 'cancelled'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('status_booking', ['pending', 'booked', 'done'])->default('pending')->change();
        });
    }
};
