<?php

namespace Tests\Browser\App\Admin\Announcement;

use App\Enums\Role;
use App\Models\Announcement;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;

/**
 * @group admin
 */
class AnnouncementTest extends AdminFrontendDuskTestCase
{

    public function testAdminAnnouncement(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $announcement = Announcement::factory()->make();

        $this->browse(function (Browser $browser) use ($announcement) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Duyurular')
                ->pause(1000)
                ->storeConsoleLog('announcements.index')
                ->screenshot('announcements/admin/index')
                ->waitForLocation('/announcements');

            $browser->press('Oluştur')
                ->waitFor('input[name="name"]')
                ->attach('input[name="src"]', $announcement->src)
                ->type('input[name="name"]', $announcement->name)
                ->pause(1000);

            $browser->script("document.querySelector('.ql-editor > p').innerHTML = '" . $announcement->content . "';");

            $browser->screenshot('announcements/admin/create')
                ->press('Kaydet')
                ->waitForLocation('/announcements')
                ->pause(1000)
                ->assertSeeIn('table', $announcement->name)
                ->storeConsoleLog('announcements.last')
                ->screenshot('announcements/admin/create.index');

/*            $browser->click('table > tbody > tr:first-child > td > div > a')
                ->pause(2000)
                ->type('input[name="name"]', 'Updated ' . $announcement->name)
                ->press('Kaydet')
                ->pause(1500)
                ->screenshot('announcements/admin/edit')
                ->back()
                ->screenshot('announcements/admin/edit.back')
                ->waitForText('Updated ' . $announcement->name, 10)
                ->assertSeeIn('table', 'Updated ' . $announcement->name)
                ->screenshot('announcements/admin/edit.index')
                ->storeConsoleLog('announcements.edit');*/

            $browser->click('table > tbody > tr:first-child > td > div > button:nth-child(3)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table',  $announcement->name)
                ->storeConsoleLog('announcements.delete')
                ->screenshot('announcements/admin/delete');
        });
    }
}
