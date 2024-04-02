<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;

abstract class StudentFrontendDuskTestCase extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Get the base URL for Dusk's default driver.
     */
    protected function baseUrl(): string
    {
        return env('FRONTEND_URL', 'http://localhost');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->deleteCookie(config('session.cookie'));
            $browser->deleteCookie('XSRF-TOKEN');
        });
    }
}
