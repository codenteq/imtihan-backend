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

    /*
     * Get all categories in tree structure
     */
    public function getTreeCategories()
    {
        $questionCategory = $this->model::whereNull('parent_id')->get();

        return $questionCategory->load('childrenTree');
    }
}
