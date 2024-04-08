<?php

namespace Tests\Browser\App\Student\Support;

use App\Enums\Role;
use App\Models\Support;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\StudentFrontendDuskTestCase;

/**
 * @group student
 */
class SupportTest extends StudentFrontendDuskTestCase
{
    public function testSupport(): void
    {
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => Role::Student])
            ->create();

        $support = Support::factory()->make();

        $this->browse(function (Browser $browser) use ($support) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'student@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Destek')
                ->storeConsoleLog('supports.index')
                ->screenshot('supports/index')
                ->waitForLocation('/support', 3);

            $browser->pressAndWaitFor('Destek Oluştur')
                ->type('input[name="subject"]', $support->subject)
                ->type('textarea[name="message"]', $support->message)
                ->storeConsoleLog('supports.create')
                ->screenshot('supports/create')
                ->press('Kaydet')
                ->pause(1000)
                ->assertSeeIn('table', $support->subject)
                ->storeConsoleLog('supports.last')
                ->screenshot('supports/create.index');

            $browser->press('table > tbody > tr:first-child > td > div > button')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('', 'Updated '.$support->subject)
                ->storeConsoleLog('supports.delete')
                ->screenshot('supports/delete');
        });
    }
}
