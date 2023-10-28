<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'is_image_option',
        'difficulty',
        'src',
        'language_id',
    ];

    /**
     * Get the category that owns the Question
     */
    public function category(): HasOne
    {
        return $this->hasOne(QuestionCategory::class, 'id', 'category_id');
    }

    /**
     * Get the language that owns the Question
     */
    public function language(): HasOne
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    /**
     * Get all the options for the Question
     */
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class, 'question_id', 'id');
    }
}
