<?php

namespace Browser\Admin\Lesson;

use App\Enums\Role;
use App\Models\Lesson;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;


class LessonTest extends AdminFrontendDuskTestCase
{
    public function testLesson(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $lesson = Lesson::factory()->make();

        $this->browse(function (Browser $browser) use ($lesson) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Dersler')
                ->storeConsoleLog('lessons.index')
                ->screenshot('lessons/index')
                ->waitForLocation('/lessons', 3);

            $browser->press('Oluştur')
                ->waitForLocation('/lessons/create', 3)
                ->type('input[name="name"]', $lesson->name)
                ->select('select[name="language_id"]', $lesson->language_id)
                ->select('select[name="category_id"]', $lesson->category_id);

            $browser->script("document.querySelector('.ql-editor > p').innerHTML = '".$lesson->content."';");

            $browser->screenshot('lessons/create')
                ->press('Kaydet')
                ->pause(3000)
                ->assertSeeIn('table', $lesson->name)
                ->storeConsoleLog('lessons.last')
                ->screenshot('lessons/create.index');

            $browser->click('table > tbody > tr:first-child > td > div > a')
                ->pause('1000')
                ->type('input[name="name"]', 'Updated '.$lesson->name)
                ->press('Kaydet')
                ->screenshot('lessons/edit')
                ->back()
                ->waitForText('Updated '.$lesson->name, 10)
                ->assertSeeIn('table', 'Updated '.$lesson->name)
                ->storeConsoleLog('lessons.edit')
                ->screenshot('lessons/edit.index');

            $browser->press('table > tbody > tr:first-child > td > div > button:nth-child(3)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table', 'Updated '.$lesson->name)
                ->storeConsoleLog('lessons.delete')
                ->screenshot('lessons/delete');
        });
    }
}
