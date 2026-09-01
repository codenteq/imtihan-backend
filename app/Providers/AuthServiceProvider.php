<?php

namespace App\Providers;

use App\Models\ClassSchedule;
use App\Models\Note;
use App\Models\Support;
use App\Policies\Student\ClassSchedule\ClassSchedulePolicy;
use App\Policies\Student\Note\NotePolicy;
use App\Policies\Student\Support\SupportPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Support::class => SupportPolicy::class,
        Note::class => NotePolicy::class,
        ClassSchedule::class => ClassSchedulePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('app.frontend_url')."/auth/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
