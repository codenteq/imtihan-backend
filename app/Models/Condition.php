<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Condition extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'exam_type_id',
        'exam_type_category_id',
        'condition_category',
        'value',
        'is_active',
    ];

    protected $casts = [
        'condition_category' => \App\Enums\ConditionCategory::class
    ];

    public function examTypeCategory(): BelongsTo
    {
        return $this->belongsTo(ExamTypeCategory::class, 'exam_type_category_id', 'id');
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'condition_index';
    }
}
