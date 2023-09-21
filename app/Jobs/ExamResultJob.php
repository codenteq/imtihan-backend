<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExamResultJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    protected $testId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $testId)
    {
        $this->userId = $userId;
        $this->testId = $testId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    }
}
