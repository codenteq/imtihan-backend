<?php

namespace App\Services\Admin\Condition;

use App\Models\Condition;
use App\Services\Base\BaseService;

class ConditionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Condition::class);
    }
}
