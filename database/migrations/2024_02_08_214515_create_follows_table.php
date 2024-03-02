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
        Schema::create('follows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('trackable_id');
            $table->string('trackable_type', 128);
            $table->foreignUuid('follower_id')->constrained('consumers', 'id')->cascadeOnUpdate()->cascadeOnDelete();//consumer_id

            $table->index(['trackable_id', 'trackable_type']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
