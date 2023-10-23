<?php

namespace App\Policies\Student\ClassSchedule;

use App\Models\ClassSchedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ClassSchedulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassSchedule $classSchedule): Response|bool
    {
        return $user->id === $classSchedule->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassSchedule $classSchedule): Response|bool
    {
        return $user->id === $classSchedule->user_id;
    }
}
