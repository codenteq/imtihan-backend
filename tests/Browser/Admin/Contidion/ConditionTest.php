<?php

namespace Tests\Browser\Admin\Contidion;

use App\Enums\ConditionCategory;
use App\Enums\Role;
use App\Models\Condition;
use App\Models\ExamType;
use App\Models\ExamTypeCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;
use Tests\DuskTestCase;

class ConditionTest extends AdminFrontendDuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testCondition(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();


        $examType = ExamType::factory()->create();
        $examTypeCategory = ExamTypeCategory::factory()
            ->state(['exam_type_id' => $examType->id])->create();

        $condition = Condition::factory()
            ->state(['exam_type_id' => $examType->id])
            ->state(['exam_type_category_id' => $examTypeCategory->id])
            ->make();

        $this->browse(function (Browser $browser) use ($condition) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Koşullar')
                ->storeConsoleLog('conditions.index')
                ->screenshot('conditions/index')
                ->waitForLocation('/conditions');

            $browser->pressAndWaitFor('Oluştur')
                ->waitFor('input[name="name"]')
                ->type('input[name="name"]', $condition->name)
                ->type('input[name="value"]', $condition->value)

                ->select('select[name="exam_type_id"]', $condition->exam_type_id)
                ->waitForInput('exam_type_id', $condition->exam_type_id)
                ->select('select[name="exam_type_category_id"]', $condition->exam_type_category_id)
                ->waitForInput('exam_type_category_id', $condition->exam_type_category_id)

                ->select('select[name="condition_category"]', ConditionCategory::Time->value)
                ->press('Kaydet')
                ->pause(1000)
                ->assertSeeIn('table', $condition->name)
                ->storeConsoleLog('conditions.last')
                ->screenshot('conditions/create.index');

            $browser->press('table > tbody > tr:first-child > td > div > button')
                ->waitFor('input[name="name"]')
                ->type('input[name="name"]', 'Updated ' . $condition->name)
                ->press('Kaydet')
                ->waitForText('Updated ' . $condition->name, 10)
                ->assertSeeIn('table', 'Updated ' . $condition->name)
                ->storeConsoleLog('conditions.edit')
                ->screenshot('conditions/edit');

            $browser->press('table > tbody > tr:first-child > td > div > button:nth-child(2)')
                ->acceptDialog()
                ->pause(1000)
                ->assertDontSeeIn('table', 'Updated ' . $condition->name)
                ->storeConsoleLog('conditions.delete')
                ->screenshot('conditions/delete');

        });
    }
}
