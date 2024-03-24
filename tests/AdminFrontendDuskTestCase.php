<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTruncation;
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
}
