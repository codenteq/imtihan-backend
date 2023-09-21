<?php

namespace App\Services\Student\Announcement;

use App\Models\Announcement;
use App\Services\Base\BaseService;

class AnnouncementService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Announcement::class);
    }
}
