<?php

namespace App\Services\Student\Exam;

use App\Enums\ConditionCategory;
use App\Enums\CustomExam;
use App\Jobs\ExamResultJob;
use App\Models\Condition;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamUserAnswer;
use App\Models\Question;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\DB;
use Laravel\Octane\Facades\Octane;

class ExamService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Exam::class);
    }

    public function create(object $request): array
    {
        return DB::transaction(function () use ($request) {
            $exam = $this->model::create([
                'name' => 'Exam ' . now()->format('Y-m-d H:i:s'),
                'user_id' => $request->user_id,
                'exam_type_id' => $request->type === 'normal' ? $request->id : null,
            ]);

            $questionData = collect();

            if ($request->type === 'normal') {
                $conditions = Condition::whereIsActive(true)
                    ->whereExamTypeId($request->id)
                    ->whereConditionCategory(ConditionCategory::Length->value)
                    ->with('examTypeCategory')
                    ->get();

                $conditions->each(function ($condition) use ($exam, &$questionData) {
                    $questions = Question::where('category_id', $condition->examTypeCategory->question_category_id)
                        ->with(['options', 'category'])
                        ->limit($condition->id)
                        ->inRandomOrder()->get();

                    $questions->each(function ($question) use ($exam, &$questionData) {

                        $questionData->push($question);

                        ExamQuestion::create([
                            'exam_id' => $exam->id,
                            'question_id' => $question->id,
                        ]);
                    });
                });
            } else {
                $questions = Question::where('category_id', $request->id)
                    ->limit(CustomExam::ExamQuestionLength->value)->inRandomOrder()->get();

                $questions->each(function ($question) use ($exam, &$questionData) {
                    $questionData->push($question);

                    ExamQuestion::create([
                        'exam_id' => $exam->id,
                        'question_id' => $question->id,
                    ]);
                });
            }

            return [
                'exam_id' => $exam->id,
                'time' => $this->getExamTime($request),
                'questions' => $questionData
            ];
        });
    }

    private function getExamTime($request): int
    {
        return $request->type == 'normal'
            ? Condition::whereExamTypeId($request->id)->whereConditionCategory(ConditionCategory::Time)->first()->value
            : CustomExam::ExamQuestionLength->value;
    }

    public function storeUserAnswer(int $exam, object $request): array
    {
        $answers = [];

        foreach ($request->answers as $answer) {
            $answers[] = ExamUserAnswer::create([
                'exam_id' => $exam,
                'question_id' => $answer['question_id'],
                'answer_id' => $answer['answer_id'],
                'user_id' => auth()->id(),
            ]);
        }

        ExamResultJob::dispatch($exam)->onQueue('exam_result');

        return $answers;
    }

    public function destroy(int $id, array $where = []): mixed
    {
        $exam = $this->model::findOrFail($id);

        ExamQuestion::whereExamId($id)->delete();
        $exam->delete();

        return $exam;
    }
}
