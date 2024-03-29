<?php

namespace Tests\Browser\Student\Announcement;

use App\Enums\Role;
use App\Models\Announcement;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\StudentFrontendDuskTestCase;

class AnnouncementTest extends StudentFrontendDuskTestCase
{
    public function testAnnouncement(): void
    {
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => Role::Student])
            ->create();

        $announcement = Announcement::factory()->create();

        $this->browse(function (Browser $browser) use ($announcement) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'student@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Duyurular')
                ->screenshot('announcements/dashboard')
                ->storeConsoleLog('announcements.index')
                ->waitForLocation('/announcement', 3)
                ->screenshot('announcements/index');

            $browser->click('.announcement-card svg')
                ->screenshot("announcements/view")
                ->click('#view')
                ->pause("1000")
                ->assertSeeIn('h1', $announcement->name)
                ->storeConsoleLog('announcements.view')
                ->screenshot('announcements/view.index');
        });
    }
}
