<?php

use App\Enums\Difficulty;
use App\Enums\QuestionStatus;
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
        $questionStatus = [
            QuestionStatus::Draft->value,
            QuestionStatus::Pending->value,
            QuestionStatus::Published->value,
        ];

        $difficulty = [
            Difficulty::Easy->value,
            Difficulty::Medium->value,
            Difficulty::Hard->value,
        ];

        Schema::create('questions', function (Blueprint $table) use ($questionStatus, $difficulty) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('category_id')->index();
            $table->boolean('is_image_option')->default(false);
            $table->string('src')->nullable();
            $table->foreignId('language_id')->index();
            $table->enum('difficulty', $difficulty);
            $table->enum('status', $questionStatus)->default(QuestionStatus::Draft->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
