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
            Schema::create('maintenance_feedback', function (Blueprint $table) {
                $table->id();
                $table->foreignId('maintenance_report_id')
                    ->constrained('maintenance_reports')
                    ->onDelete('cascade');
                $table->text('feedback');
                $table->foreignId('created_by')
                    ->constrained('users')
                    ->onDelete('cascade');
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_feedback');
    }
};
