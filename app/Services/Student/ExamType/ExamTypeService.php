<?php

namespace App\Services\Student\ExamType;

use App\Models\ExamType;
use App\Services\Base\BaseService;

class ExamTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(ExamType::class);
    }
}
