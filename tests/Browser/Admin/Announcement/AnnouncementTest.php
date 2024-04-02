<?php

namespace Tests\Browser\Admin\Announcement;

use App\Enums\Role;
use App\Models\Announcement;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;

class AnnouncementTest extends AdminFrontendDuskTestCase
{
    /**
     * A Dusk test announcement.
     */
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
                ->storeConsoleLog('announcements.index')
                ->screenshot('announcements/index')
                ->waitForLocation('/announcements');

            $browser->pressAndWaitFor('Oluştur')
                ->waitFor('input[name="name"]')
                ->attach('input[name="src"]', $announcement->src)
                ->type('input[name="name"]', $announcement->name)
                ->pause(1000);

            $browser->script("document.querySelector('.ql-editor > p').innerHTML = '" . $announcement->content . "';");

            $browser->screenshot('announcements/create')
                ->pressAndWaitFor('Kaydet')
                ->waitForLocation('/announcements')
                ->assertSeeIn('table', $announcement->name)
                ->storeConsoleLog('announcements.last')
                ->screenshot('announcements/create.index');

            $browser->click('table > tbody > tr:first-child > td > div > a')
                ->pause(1000)
                ->type('input[name="name"]', 'Updated ' . $announcement->name)
                ->press('Kaydet')
                ->screenshot('announcements/edit')
                ->back()
                ->waitForText('Updated ' . $announcement->name, 10)
                ->assertSeeIn('table', 'Updated ' . $announcement->name)
                ->storeConsoleLog('announcements.edit');

            $browser->click('table > tbody > tr:first-child > td > div > button:nth-child(3)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table', 'Updated ' . $announcement->name)
                ->storeConsoleLog('announcements.delete')
                ->screenshot('announcements/delete');
        });
    }
}
