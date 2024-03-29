<?php


use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;

abstract class StudentFrontendDuskTestCase extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Get the base URL for Dusk's default driver.
     *
     * @return string
     */
    protected function baseUrl()
    {
        return env('FRONTEND_URL', 'http://localhost');
    }
}
