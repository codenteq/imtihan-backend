<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamTypeCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'exam_type_id',
        'question_category_id',
    ];

    public function questionCategory(): HasOne
    {
        return $this->hasOne(QuestionCategory::class, 'id', 'question_category_id');
    }
}
