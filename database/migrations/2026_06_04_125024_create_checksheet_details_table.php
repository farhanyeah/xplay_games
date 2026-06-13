<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checksheet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checksheet_header_id')->constrained('checksheet_headers')->onDelete('cascade');
            $table->foreignId('checksheet_item_id')->constrained('checksheet_items')->onDelete('cascade');
            $table->enum('status', ['done', 'not_done'])->default('not_done');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checksheet_details');
    }
};