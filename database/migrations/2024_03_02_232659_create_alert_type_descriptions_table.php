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
        Schema::create('alert_type_descriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('alert_type_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('language_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_type_descriptions');
    }
};
