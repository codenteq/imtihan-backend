<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTruncation;

abstract class StudentFrontendDuskTestCase extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Get the base URL for Dusk's default driver.
     *
     * @return string
     */
    protected function baseUrl(): string
    {
        return env('FRONTEND_URL', 'http://localhost');
    }
}
