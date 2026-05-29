<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class ExamType extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'src',
        'language_id',
    ];

    public function language()
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    public function questionCategories(): BelongsToMany
    {
        return $this->belongsToMany(ExamTypeCategory::class, 'exam_type_categories', 'exam_type_id', 'question_category_id');
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'exam_type_index';
    }
}
