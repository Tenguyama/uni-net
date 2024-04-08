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
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('postable_id');
            $table->string('postable_type', 128);
            $table->foreignUuid('theme_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('description');
            $table->timestamps();

            $table->index(['postable_id', 'postable_type']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
