<?php

namespace Tests\Browser\App\Student\Exam;

use App\Enums\Role;
use App\Models\Condition;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\ExamTypeCategory;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionOption;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\StudentFrontendDuskTestCase;

/**
 * @group student
 */
class ExamTest extends StudentFrontendDuskTestCase
{
    public function normalExamSetup(): void
    {
        $category = QuestionCategory::factory()
            ->state(['parent_id' => null])
            ->create();

        $examType = ExamType::factory()->state(['name' => 'Custom'])->create();

        $examTypeCategory = ExamTypeCategory::factory()->state([
            'exam_type_id' => $examType->id,
            'question_category_id' => $category->id,
        ])->create();

        Condition::factory()->state([
            'name' => 'Question Length',
            'condition_category' => \App\Enums\ConditionCategory::Length->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => $examTypeCategory->id,
            'value' => 10,
            'is_active' => true,
        ])->create();

        Condition::factory()->state([
            'name' => 'Exam Time',
            'condition_category' => \App\Enums\ConditionCategory::Time->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => null,
            'value' => 15,
            'is_active' => true,
        ])->create();

        Condition::factory()->state([
            'name' => 'Exam Penalty Ratio',
            'condition_category' => \App\Enums\ConditionCategory::PenaltyRatio->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => null,
            'value' => 4,
            'is_active' => true,
        ])->create();

        Condition::factory()->state([
            'name' => 'Exam Max Score',
            'condition_category' => \App\Enums\ConditionCategory::MaxScore->value,
            'exam_type_id' => $examType->id,
            'exam_type_category_id' => null,
            'value' => 500,
            'is_active' => true,
        ])->create();

        for ($i = 0; $i < 20; $i++) {
            $question = Question::factory()->state(['category_id' => $category->id])->create();

            for ($j = 0; $j < 4; $j++) {
                $isCorrect = $j === 0;
                QuestionOption::factory()
                    ->state(['is_correct' => $isCorrect])
                    ->for($question)->create();
            }
        }
    }


    /**
     * A Dusk test example.
     */
    public function testNormalExam(): void
    {
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => Role::Student])
            ->create();

        $this->normalExamSetup();

        $this->browse(function (Browser $browser) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'student@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('İmtihanlar')
                ->pause(1500)
                ->storeConsoleLog('exams')
                ->screenshot('exams/index')
                ->click('.exam')
                ->pause(2000)
                ->screenshot('exams/show');

            for ($i = 0; $i <= 2; $i++) {
                $browser->click('#answers > li:first-child > span')
                    ->press('Sonraki')
                    ->pause(500);
            }

            for ($i = 0; $i <= 2; $i++) {
                $browser->click('#answers > li:nth-child(3) > span')
                    ->press('Sonraki')
                    ->pause(500);
            }

            for ($i = 0; $i <= 2; $i++) {
                $browser->press('Sonraki')
                    ->pause(500);
            }

            $browser->screenshot('exams/finish.answer')
                ->press('Sınavı Bitir')
                ->pause(1500)
                ->storeConsoleLog('exams.finish')
                ->screenshot('exams/exam.finish')
                ->assertSee('150');
        });
    }


    public function testCustomExam()
    {
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => Role::Student])
            ->create();

        $category = QuestionCategory::factory()
            ->state(['parent_id' => null])
            ->state(['name' => 'Sayısal'])
            ->create();

        $category2 = QuestionCategory::factory()
            ->state(['parent_id' => $category->id])
            ->state(['name' => 'Matematik'])
            ->create();

        $category3 = QuestionCategory::factory()
            ->state(['parent_id' => $category2->id])
            ->state(['name' => 'Geometri'])
            ->create();

        for ($i = 0; $i < 20; $i++) {
            $question = Question::factory()->state(['category_id' => $category3->id])->create();

            for ($j = 0; $j < 4; $j++) {
                $isCorrect = $j === 0;
                QuestionOption::factory()
                    ->state(['is_correct' => $isCorrect])
                    ->for($question)->create();
            }
        }

        $this->browse(function (Browser $browser) use ($category3){
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'student@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('İmtihanlar')
                ->pause(1500)
                ->storeConsoleLog('exams')
                ->screenshot('exams/index')
                ->press('#custom-exam-btn')
                ->pause(2000)
                ->screenshot('exams/custom.show');

            $browser->select('select[name="category_id"]', $category3->id)
                ->pause(1000)
                ->screenshot('exams/category.select')
                ->press('Sınavı Başlat')
                ->pause(2000)
                ->screenshot('exams/custom.exam.start');

            for ($i = 0; $i <= 2; $i++) {
                $browser->click('#answers > li:first-child > span')
                    ->press('Sonraki')
                    ->pause(500);
            }

            for ($i = 0; $i <= 2; $i++) {
                $browser->click('#answers > li:nth-child(3) > span')
                    ->press('Sonraki')
                    ->pause(500);
            }

            for ($i = 0; $i <= 2; $i++) {
                $browser->press('Sonraki')
                    ->pause(500);
            }

            $browser->screenshot('exams/finish.answer')
                ->press('Sınavı Bitir')
                ->pause(1500)
                ->storeConsoleLog('exams.finish')
                ->screenshot('exams/exam.finish')
                ->assertSee('30');
        });
    }
}
