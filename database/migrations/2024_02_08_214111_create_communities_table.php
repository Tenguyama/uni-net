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
        Schema::create('communities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nickname')->unique();
            $table->string('description');
            $table->foreignUuid('consumer_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_locked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
