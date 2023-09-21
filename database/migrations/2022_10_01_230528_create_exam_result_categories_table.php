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
        Schema::create('exam_result_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('total_questions');
            $table->integer('correct');
            $table->integer('in_correct');
            $table->integer('blank');
            $table->foreignId('category_id')->index();
            $table->foreignId('exam_id')->index();
            $table->foreignId('user_id')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_result_categories');
    }
};
