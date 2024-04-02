<?php

namespace Tests\Browser\Admin\User;

use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\Role;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;

class UserTest extends AdminFrontendDuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testUser(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $user = User::factory()
            ->state(['role' => Role::Student->value])
            ->state(['gender' => Gender::Male->value])
            ->state(['phone' => '905555555555'])
            ->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Kullanıcılar')
                ->storeConsoleLog('users.index')
                ->screenshot('users/index')
                ->waitForLocation('/users', 3);

            $browser->press('Oluştur')
                ->waitForLocation('/users/create', 3)
                ->type('input[name="full_name"]', $user->full_name)
                ->select('select[name="gender"]', $user->gender)
                ->type('input[name="email"]', $user->email)
                ->type('input[name="phone"]', $user->phone)
                ->select('select[name="education_level"]', EducationLevel::Primary->value)
                ->type('input[name="birth_date"]', now()->format('Y-m-d'))
                ->select('select[name="role"]', $user->role)
                ->screenshot('users/create')
                ->press('Kaydet')
                ->pause(3000)
                ->assertSeeIn('table', $user->full_name)
                ->storeConsoleLog('users.last')
                ->screenshot('users/create.index');

            $browser->click('table > tbody > tr:first-child > td > div > a')
                ->pause('1000')
                ->type('input[name="full_name"]', 'Updated '.$user->full_name)
                ->press('Kaydet')
                ->screenshot('users/edit')
                ->back()
                ->waitForText('Updated '.$user->full_name, 10)
                ->assertSeeIn('table', 'Updated '.$user->full_name)
                ->storeConsoleLog('users.edit');

            $browser->press('table > tbody > tr:first-child > td > div > button:nth-child(3)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table', 'Updated '.$user->full_name)
                ->storeConsoleLog('users.delete')
                ->screenshot('users/delete');

        });
    }
}
