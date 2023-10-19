<?php

namespace App\Services\Student\StaticPage;

use App\Models\StaticPage;
use App\Services\Base\BaseService;

class StaticPageService extends BaseService
{
    public function __construct()
    {
        parent::__construct(StaticPage::class);
    }
}
