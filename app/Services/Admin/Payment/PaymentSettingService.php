<?php

namespace App\Services\Admin\Payment;

use App\Models\PaymentSetting;
use App\Services\Base\BaseService;

class PaymentSettingService extends BaseService
{
    public function __construct()
    {
        parent::__construct(PaymentSetting::class);
    }
}
