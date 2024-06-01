<?php

namespace Tests\Browser\App\Admin\Question;

use App\Enums\QuestionStatus;
use App\Enums\Role;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;


/**
 * @group admin
 */
class QuestionTest extends AdminFrontendDuskTestCase
{
    public function testAdminQuestion(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $question = Question::factory()->make();
        $options = QuestionOption::factory(4)->make();

        $this->browse(function (Browser $browser) use ($question, $options){
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Sorular')
                ->pause(1000)
                ->storeConsoleLog('questions.index')
                ->screenshot('questions/admin/index')
                ->waitForLocation('/questions');

            $browser->press('Oluştur')
                ->waitFor('input[name="name"]')
                ->type('input[name="name"]', $question->name)
                ->select('select[name="difficulty"]', $question->difficulty)
                ->select('select[name="status"]', QuestionStatus::Draft->value);

            $browser->script("document.querySelector('.ql-editor > p').innerHTML = '" . $question->description . "';");

            $browser->pause(1500)
                ->select('select[name="language_id"]', $question->language_id)
                ->assertSelected('select[name="language_id"]', $question->language_id)
                ->select('select[name="category_id"]', $question->category_id)
                ->assertSelected('select[name="category_id"]', $question->category_id)
                ->type('input[name="options.0.description"]', $options[0]->description)
                ->type('input[name="options.1.description"]', $options[1]->description)
                ->type('input[name="options.2.description"]', $options[2]->description)
                ->type('input[name="options.3.description"]', $options[3]->description)
                ->radio('#is_correct_0',true)
                ->press("Kaydet")
                ->pause(3000)
                ->screenshot('questions/admin/create')
                ->assertSeeIn('table', $question->name)
                ->storeConsoleLog('categories.last')
                ->screenshot('questions/admin/create.index');

            $browser->click('table > tbody > tr:first-child > td > div > a')
                ->screenshot('questions/admin/show.edit')
                ->pause(4000)
                ->type('input[name="name"]', 'Updated '.$question->name)
                ->assertInputValue('input[name="name"]', 'Updated '.$question->name)
                ->pause(1500)
                ->press('Kaydet')
                ->screenshot('questions/admin/edit')
                ->screenshot('questions/admin/edit.index')
                ->pause(5000)
                ->waitForText('Updated '.$question->name, 10)
                ->assertSeeIn('table', 'Updated '.$question->name)
                ->storeConsoleLog('questions.edit');

            $browser->press('table > tbody > tr:first-child > td > div > button')
                ->acceptDialog()
                ->pause(1000)
                ->assertDontSeeIn('table', 'Updated '.$question->name)
                ->storeConsoleLog('questions.delete')
                ->screenshot('questions/admin/delete.index');
        });
    }
}
