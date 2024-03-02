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
        Schema::create('consumers_themes', function (Blueprint $table) {
            $table->foreignUuid('consumer_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('theme_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_pinned');//це навряд треба
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers_themes');
    }
};
