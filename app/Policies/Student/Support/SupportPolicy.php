<?php

namespace App\Policies\Student\Support;

use App\Models\Support;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SupportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Support $support): Response|bool
    {
        return $user->id === $support->user_id;
    }
}
