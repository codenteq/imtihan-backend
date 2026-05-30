<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'referenceCode',
        'productReferenceCode',
        'name',
        'price',
        'currencyCode',
        'paymentInterval',
        'paymentIntervalCount',
        'planPaymentType',
        'recurrenceCount',
        'trialPeriodDays',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(SubscriptionProduct::class, 'productReferenceCode', 'referenceCode');
    }
}
