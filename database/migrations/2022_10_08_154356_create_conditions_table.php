<?php

use App\Enums\ConditionCategory;
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
        $conditionCategory = [
            ConditionCategory::Length->value,
            ConditionCategory::MaxScore->value,
            ConditionCategory::Time->value,
            ConditionCategory::PenaltyRatio->value
        ];

        Schema::create('conditions', function (Blueprint $table) use (&$conditionCategory) {
            $table->id();
            $table->string('name');
            $table->foreignId('exam_type_id');
            $table->foreignId('exam_type_category_id')->nullable();
            $table->enum('condition_category', $conditionCategory);
            $table->float('value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['exam_type_id', 'exam_type_category_id']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conditions');
    }
};
