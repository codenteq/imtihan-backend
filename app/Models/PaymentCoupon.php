<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class PaymentCoupon extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'code',
        'discount',
        'start_date',
        'end_date',
    ];

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'payment-coupon_index';
    }
}
