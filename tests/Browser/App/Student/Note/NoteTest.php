<?php

namespace Tests\Browser\App\Student\Note;

use App\Enums\Role;
use App\Models\Note;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\StudentFrontendDuskTestCase;

/**
 * @group student
 */
class NoteTest extends StudentFrontendDuskTestCase
{
    public function testNote(): void
    {
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => Role::Student])
            ->create();

        $note = Note::factory()->make();

        $this->browse(function (Browser $browser) use ($note) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'student@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Notlar')
                ->storeConsoleLog('notes.index')
                ->screenshot('notes/index')
                ->waitForLocation('/note', 3);

            $browser->click('#create')
                ->pause(1000)
                ->waitForLocation('/note/create', 3)
                ->type('input[name="name"]', $note->name);

            $browser->script("document.querySelector('.ql-editor > p').innerHTML = '".$note->content."';");

            $browser->screenshot('notes/create')
                ->press('Kaydet')
                ->pause(3000)
                ->assertSeeIn('.note-card', $note->name)
                ->storeConsoleLog('notes.last')
                ->screenshot('notes/create.index');

            $browser->click('.note-card svg')
                ->click('#edit')
                ->pause('1000')
                ->type('input[name="name"]', 'Updated '.$note->name)
                ->press('Kaydet')
                ->back()
                ->pause(3000)
                ->screenshot('notes/edit')
                ->waitForText('Updated '.$note->name, 10)
                ->assertSeeIn('.note-card', 'Updated '.$note->name)
                ->storeConsoleLog('notes.edit')
                ->screenshot('notes/edit.index');

            $browser->press('.note-card svg')
                ->click('#remove')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('', 'Updated '.$note->name)
                ->storeConsoleLog('notes.delete')
                ->screenshot('notes/delete');
        });
    }
}
