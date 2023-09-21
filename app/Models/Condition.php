<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Condition extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 0;

    protected $fillable = [
        'name',
        'question_category_id',
        'condition_category_id',
        'value',
        'is_active',
    ];

    public function category(): HasOne
    {
        return $this->hasOne(ConditionCategory::class, 'id', 'condition_category_id');
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'condition_index';
    }
}
