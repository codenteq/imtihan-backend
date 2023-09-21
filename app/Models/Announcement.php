<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'content',
        'src',
    ];

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'announcement_index';
    }
}
