<?php

namespace Tests\Browser\Admin\Exam;

use App\Enums\Role;
use App\Models\ExamType;
use App\Models\QuestionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;
use Tests\DuskTestCase;

class ExamTypeTest extends AdminFrontendDuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testExamType(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $examType = ExamType::factory()->make();
        $questionCategory = QuestionCategory::factory(5)->create();

        $this->browse(function (Browser $browser) use ($examType, $questionCategory) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('İmtihanlar')
                ->storeConsoleLog('exam-types.index')
                ->screenshot('exam-types/index')
                ->waitForLocation('/exams', 3);

            $browser->press('Oluştur')
                ->waitFor('input[name="name"]', 3)
                ->type('input[name="name"]', $examType->name)
                ->select('select[name="language_id"]', $examType->language_id)
                ->check('input[id="category-' . $questionCategory->first()->id . '"]')
                ->check('input[id="category-' . $questionCategory->last()->id . '"]')
                ->screenshot('exam-types/create')
                ->press('Kaydet')
                ->pause(3000)
                ->assertSeeIn('table', $examType->name)
                ->storeConsoleLog('exam-types.last')
                ->screenshot('exam-types/create.index');

            $browser->click('table > tbody > tr:first-child > td > div > a')
                ->waitFor('input[name="name"]')
                ->type('input[name="name"]', 'Updated ' . $examType->name)
                ->check('input[id="category-' . $questionCategory->last()->id . '"]')
                ->press('Kaydet')
                ->screenshot("exam-types/edit")
                ->back()
                ->waitForText('Updated ' . $examType->name, 10)
                ->assertSeeIn('table', 'Updated ' . $examType->name)
                ->storeConsoleLog('exam-types.edit')
                ->screenshot('exam-types/edit.index');

            $browser->press('table > tbody > tr:first-child > td > div > button')
                ->acceptDialog()
                ->pause(1000)
                ->assertDontSeeIn('table', 'Updated ' . $examType->name)
                ->storeConsoleLog('exam-types.delete')
                ->screenshot('exam-types/delete.index');
        });
    }
}
