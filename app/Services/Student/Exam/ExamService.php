<?php

namespace App\Services\Student\Exam;

use App\Jobs\ExamResultJob;
use App\Models\Condition;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamUserAnswer;
use App\Models\Question;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\DB;

class ExamService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Exam::class);
    }

    public function create(object $request): object
    {
        $exam = null;
        DB::transaction(function () use ($request, &$exam) {
            $exam = $this->model::create([
                'name' => 'Exam '.now()->format('Y-m-d H:i:s'),
                'user_id' => $request->user_id,
            ]);

            if ($request->type === 'normal') {
                $conditions = Condition::where('is_active', true)->with('category')->whereRelation('category', 'key', 'length')->get();
                $conditions->each(function ($condition) use ($exam) {
                    $questions = Question::where('category_id', $condition->question_category_id)->inRandomOrder()->get();
                    $questions->each(function ($question) use ($exam) {
                        ExamQuestion::create([
                            'exam_id' => $exam->id,
                            'question_id' => $question->id,
                        ]);
                    });
                });
            } else {
                $request->categories->each(function ($category) use ($exam) {
                    $questions = Question::where('category_id', $category)->inRandomOrder()->get();
                    $questions->each(function ($quetion) use ($exam) {
                        ExamQuestion::create([
                            'exam_id' => $exam->id,
                            'question_id' => $quetion->id,
                        ]);
                    });
                });
            }
        });

        return $exam;
    }

    public function storeUserAnswer(object $request): array
    {
        $answers[] = null;
        $request->answers->each(function ($answer) use ($request, &$answers) {
            $answers[] = ExamUserAnswer::create([
                'exam_id' => $request->exam_id,
                'question_id' => $answer->question_id,
                'answer_id' => $answer->answer_id,
                'user_id' => auth()->id(),
            ]);
        });

        ExamResultJob::dispatch($request->exam_id)->onQueue('exam_result');

        return $answers;
    }
}
