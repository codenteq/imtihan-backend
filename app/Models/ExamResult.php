<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total_questions',
        'correct',
        'in_correct',
        'blank',
        'point',
        'exam_id',
        'user_id',
    ];

    public function category(): HasMany
    {
        return $this->hasMany(ExamResultCategory::class, 'exam_id', 'exam_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
