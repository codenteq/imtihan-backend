<?php

namespace App\Services\Student\QuestionCategory;

use App\Models\QuestionCategory;
use App\Services\Base\BaseService;

class QuestionCategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(QuestionCategory::class);
    }

    public function list(array $with = [], array $where = []): mixed
    {
        return $this->model::with($with)->whereNull('parent_id')->latest()->get();
    }
}
