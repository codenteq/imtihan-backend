<?php

namespace App\Services\Student\ClassSchedule;

use App\Models\ClassSchedule;
use App\Services\Base\BaseService;

class ClassScheduleService extends BaseService
{
    public function __construct()
    {
        parent::__construct(ClassSchedule::class);
    }
}
