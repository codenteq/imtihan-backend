<?php

namespace Tests\Browser\Admin\StaticPage;

use App\Enums\Role;
use App\Models\StaticPage;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;


class StaticPageTest extends AdminFrontendDuskTestCase
{

    public function testStaticPage(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $staticPage = StaticPage::factory()->make();

        $this->browse(function (Browser $browser) use ($staticPage) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Statik Sayfalar')
                ->storeConsoleLog('static-pages.index')
                ->screenshot('static-pages/index')
                ->waitForLocation('/static-pages');

            $browser->press('Oluştur')
                ->waitFor('input[name="name"]')
                ->type('input[name="name"]', $staticPage->name)
                ->pause(1000);

            $browser->script("document.querySelector('.ql-editor > p').innerHTML = '".$staticPage->content."';");

            $browser->screenshot('static-pages/create')
                ->press('Kaydet')
                ->waitForLocation('/static-pages')
                ->pause(1000)
                ->assertSeeIn('table', $staticPage->name)
                ->storeConsoleLog('static-pages.last')
                ->screenshot('static-pages/create.index');

            $browser->click('table > tbody > tr:first-child > td > div > a')
                ->waitFor('input[name="name"]')
                ->type('input[name="name"]', 'Updated '.$staticPage->name)
                ->press('Kaydet')
                ->screenshot('static-pages/edit')
                ->back()
                ->waitForText('Updated '.$staticPage->name, 10)
                ->assertSeeIn('table', 'Updated '.$staticPage->name)
                ->storeConsoleLog('static-pages.edit');

            $browser->click('table > tbody > tr:first-child > td > div > button:nth-child(3)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table', 'Updated '.$staticPage->name)
                ->storeConsoleLog('static-pages.delete')
                ->screenshot('static-pages/delete');

        });
    }
}
