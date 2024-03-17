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
        Schema::create('consumers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nickname')->unique();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('name');
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->foreignUuid('fakult_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('group_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('telegram_nickname')->nullable();
            $table->boolean('is_locked')->default('false');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};
