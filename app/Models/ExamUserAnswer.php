<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamUserAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'exam_id',
        'question_id',
        'user_id',
        'answer_id',
    ];
}
