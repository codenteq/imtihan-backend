<?php

namespace Tests\Browser\Admin\Language;

use App\Enums\Role;
use App\Models\Language;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;
use Tests\DuskTestCase;

class LanguageTest extends AdminFrontendDuskTestCase
{
    public function testLanguage(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $language = Language::factory()->make();

        $this->browse(function (Browser $browser) use ($language) {

            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Diller')
                ->storeConsoleLog('languages.index')
                ->screenshot('languages.index')
                ->waitForLocation('/languages');

            $browser->pressAndWaitFor('Oluştur')
                ->type('input[name="name"]', $language->name)
                ->type('input[name="code"]', $language->code)
                ->storeConsoleLog('languages.create')
                ->screenshot('languages.create')
                ->press('Kaydet')
                ->pause(1000)
                ->assertSeeIn('table', $language->name)
                ->storeConsoleLog('languages.last')
                ->screenshot('languages.create.index');

            $browser->press('table > tbody > tr:first-child > td > div > button')
                ->type('input[name="name"]', 'Updated ' . $language->name)
                ->press('Kaydet')
                ->pause(3000)
                ->assertSeeIn('table', 'Updated ' . $language->name)
                ->storeConsoleLog('languages.edit')
                ->screenshot('languages.edit');

            $browser->press('table > tbody > tr:first-child > td > div > button:nth-child(2)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table', 'Updated ' . $language->name)
                ->storeConsoleLog('languages.delete')
                ->screenshot('languages.delete');
        });
    }
}
