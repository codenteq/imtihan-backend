<?php

namespace App\Services\Admin\Condition;

use App\Models\ConditionCategory;
use App\Services\Base\BaseService;

class ConditionCategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(ConditionCategory::class);
    }
}
