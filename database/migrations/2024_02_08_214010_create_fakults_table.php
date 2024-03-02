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
        Schema::create('fakults', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->foreignUuid('language_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            //$table->string('name')->unique();
            $table->string('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fakults');
    }
};
