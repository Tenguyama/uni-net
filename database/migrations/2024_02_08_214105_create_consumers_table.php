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
            $table->string('password');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('status');
            $table->string('description');
            $table->foreignUuid('fakult_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('group_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('telegram_nickname')->nullable();
            //CSRF TOKEN
            $table->boolean('is_locked');
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
