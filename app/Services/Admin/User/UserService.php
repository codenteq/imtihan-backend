<?php

namespace App\Services\Admin\User;

use App\Enums\Role;
use App\Models\User;
use App\Services\Base\BaseService;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}
