<?php

namespace App\Services\Student\State;

use App\Models\State;
use App\Services\Base\BaseService;

class StateService extends BaseService
{
    public function __construct()
    {
        parent::__construct(State::class);
    }
}
