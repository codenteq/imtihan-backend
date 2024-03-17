<?php

namespace App\Jobs;

use App\Services\Student\ExamResult\ExamResultService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExamResultJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected int $exam_id)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        new ExamResultService($this->exam_id);
    }
}
