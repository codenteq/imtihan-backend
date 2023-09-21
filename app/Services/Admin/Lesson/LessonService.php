<?php

namespace App\Services\Admin\Lesson;

use App\Models\Lesson;
use App\Services\Base\BaseService;

class LessonService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Lesson::class);
    }
}
