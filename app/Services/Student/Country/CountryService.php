<?php

namespace App\Services\Student\Country;

use App\Models\Country;
use App\Services\Base\BaseService;

class CountryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Country::class);
    }
}
