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
        Schema::create('alert_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code_name');
            //$table->foreignUuid('language_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            //$table->string('name');
            //$table->string('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_types');
    }
};
