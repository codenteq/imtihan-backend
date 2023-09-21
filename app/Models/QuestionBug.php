<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBug extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'question_id',
    ];

    /**
     * Get the question that owns the QuestionBug
     */
    public function question(): HasOne
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
