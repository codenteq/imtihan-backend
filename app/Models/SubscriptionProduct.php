<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'referenceCode',
        'name',
        'description',
        'status',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(SubscriptionPlan::class, 'productReferenceCode', 'referenceCode');
    }
}
