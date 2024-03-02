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
        Schema::table('comments', function (Blueprint $table){
//        $table->foreignUuid('parent_id')
//            ->nullable()
//            ->references('id')
//            ->on('comments')
//            ->cascadeOnUpdate()
//            ->cascadeOnDelete();
            // Створення стовпця 'parent_id'
            $table->foreignUuid('parent_id')
                ->nullable();

            // Додавання зовнішнього ключа
            $table->foreign('parent_id')
                ->references('id')
                ->on('comments')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('comments', 'parent_id');
    }
};
