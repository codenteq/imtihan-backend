<?php

namespace App\Services\Admin\Question;

use App\Models\QuestionCategory;
use App\Services\Base\BaseService;

class QuestionCategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(QuestionCategory::class);
    }
}
