<?php

namespace App\Services\Admin\Payment;

use App\Models\PaymentMethod;
use App\Services\Base\BaseService;

class PaymentMethodService extends BaseService
{
    public function __construct()
    {
        parent::__construct(PaymentMethod::class);
    }
}
