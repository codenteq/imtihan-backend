<?php

namespace App\Services\Admin\Account;

use App\Models\User;
use App\Services\Base\BaseService;

class AccountService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}
