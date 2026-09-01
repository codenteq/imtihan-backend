<?php

namespace App\Providers;

use App\Services\Admin\Announcement\AnnouncementService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AnnouncementService::class, function ($app) {
            return new AnnouncementService;
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
