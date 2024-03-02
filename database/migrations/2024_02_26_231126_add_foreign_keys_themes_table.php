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
        Schema::table('themes', function (Blueprint $table){
//            $table->foreignUuid('parent_id')
//                ->nullable()
//                ->references('id')
//                ->on('themes')
//                ->cascadeOnUpdate()
//                ->cascadeOnDelete();
            // Створення стовпця 'parent_id'
            $table->foreignUuid('parent_id')
                ->nullable();

            // Додавання зовнішнього ключа
            $table->foreign('parent_id')
                ->references('id')
                ->on('themes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns('themes', 'parent_id');
    }
};
