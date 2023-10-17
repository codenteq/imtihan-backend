<?php

namespace App\Services\Student\City;

use App\Models\City;
use App\Services\Base\BaseService;

class CityService extends BaseService
{
    public function __construct()
    {
        parent::__construct(City::class);
    }
}
