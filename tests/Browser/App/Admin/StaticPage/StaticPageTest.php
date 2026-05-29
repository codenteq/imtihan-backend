<?php

namespace Tests\Browser\App\Admin\StaticPage;

use App\Enums\Role;
use App\Models\StaticPage;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;
use function Laravel\Prompts\pause;

/**
 * @group admin
 */
class StaticPageTest extends AdminFrontendDuskTestCase
{

    public function testStaticPage(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@codenteq.com'])
            ->state(['role' => Role::Admin])
            ->create();

        $staticPage = StaticPage::factory()->make();

        $this->browse(function (Browser $browser) use ($staticPage) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@codenteq.com')
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
                ->pause(1500)
                ->type('input[name="name"]', 'Updated '.$staticPage->name)
                ->assertInputValue('input[name="name"]', 'Updated '.$staticPage->name)
                ->pause(500)
                ->press('Kaydet')
                ->screenshot('static-pages/edit')
                ->back()
                ->pause(3000)
                ->screenshot('static-pages/edit.index')
                ->waitForText('Updated', 10)
                ->assertSeeIn('table', 'Updated')
                ->storeConsoleLog('static-pages.edit');

            $browser->click('table > tbody > tr:first-child > td > div > button:nth-child(3)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table', 'Updated')
                ->storeConsoleLog('static-pages.delete')
                ->screenshot('static-pages/delete');

        });
    }
}
