<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checksheet_headers', function (Blueprint $table) {
            $table->id();
            $table->string('checksum', 50)->unique();
            $table->date('date');
            $table->enum('shift', ['pagi', 'malam']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checksheet_headers');
    }
};