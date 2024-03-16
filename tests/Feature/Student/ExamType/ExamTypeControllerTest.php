<?php

namespace Tests\Feature\Student\ExamType;

use Tests\TestCase;

class ExamTypeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
