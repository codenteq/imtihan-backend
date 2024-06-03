<?php

namespace App\Services\Student\ExamResult;

use App\Enums\ConditionCategory;
use App\Enums\CustomExam;
use App\Models\Condition;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamUserAnswer;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamResultService
{
    public function __construct(private readonly int $exam_id, private readonly int $user_id)
    {
        $this->handle();
    }

    public function handle(): void
    {
        $correctAnswer = $this->calculateCorrectAnswer();
        $totalQuestions = ExamUserAnswer::whereExamId($this->exam_id)->count();
        $blankAnswer = ExamUserAnswer::whereExamId($this->exam_id)->whereNull('answer_id')->count();
        $inCorrectAnswer = $totalQuestions - $correctAnswer - $blankAnswer;
        $point = $this->calculateConditionPoint($correctAnswer, $inCorrectAnswer, $blankAnswer);

        info('Exam Result', [
            'total_questions' => $totalQuestions,
            'correct' => $correctAnswer,
            'in_correct' => $inCorrectAnswer,
            'blank' => $blankAnswer,
            'point' => $point,
            'exam_id' => $this->exam_id,
            'user_id' => $this->user_id,
        ]);

        DB::transaction(function () use ($totalQuestions, $point, $correctAnswer, $inCorrectAnswer, $blankAnswer) {
            ExamResult::create([
                'total_questions' => $totalQuestions,
                'correct' => $correctAnswer,
                'in_correct' => $inCorrectAnswer,
                'blank' => $blankAnswer,
                'point' => $point,
                'exam_id' => $this->exam_id,
                'user_id' => $this->user_id,
            ]);
        });
    }

    public function calculateConditionPoint(int $correctAnswer, int $inCorrectAnswer, int $blankAnswer): float
    {
        $examTypeId = Exam::whereId($this->exam_id)->first()->exam_type_id;

        $scorePerQuestion = CustomExam::Point->value;

        $maxScore = 100;

        $penaltyRatio = 0;

        if ($examTypeId) {
            $maxScore = Condition::query()->whereConditionCategory(ConditionCategory::MaxScore)
                ->whereNull('exam_type_category_id')
                ->whereExamTypeId($examTypeId)
                ->first()?->value;

            $penalty = Condition::query()->whereConditionCategory(ConditionCategory::PenaltyRatio)
                ->whereExamTypeId($examTypeId)
                ->first()?->value;

            $penaltyRatio = 1 / $penalty;

        }

        $totalCorrectScore = $correctAnswer * $scorePerQuestion;
        $totalPenalty = floor($inCorrectAnswer * $penaltyRatio) * $scorePerQuestion;
        $rawScore = $totalCorrectScore - $totalPenalty;

        $totalQuestions = $correctAnswer + $inCorrectAnswer + $blankAnswer;

        return round($rawScore / $totalQuestions * $maxScore, 5);
    }

    public function calculateCorrectAnswer(): int
    {
        $answers = ExamUserAnswer::whereExamId($this->exam_id)->get();
        $correctOptions = QuestionOption::whereIsCorrect(true)
            ->whereIn('question_id', $answers->pluck('question_id'))
            ->pluck('id', 'question_id');

        return $answers->reduce(function ($points, $answer) use ($correctOptions) {
            return $points + ($correctOptions[$answer->question_id] === $answer->answer_id ? 1 : 0);
        }, 0);
    }
}
