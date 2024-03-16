<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Support extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'subject',
        'message',
        'is_active',
        'user_id',
    ];

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'support_index';
    }
}
