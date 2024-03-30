<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

abstract class AdminFrontendDuskTestCase extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Get the base URL for Dusk's default driver.
     *
     * @return string
     */
    protected function baseUrl()
    {
        return env('ADMIN_FRONTEND_URL', 'http://localhost');
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
