<?php

namespace Tests\Browser\Admin\Account;

use App\Enums\Gender;
use App\Enums\Role;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;
use Tests\DuskTestCase;

/**
 * @group admin
 */
class AccountTest extends AdminFrontendDuskTestCase
{
    public function testAdminAccount(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();

        $user = User::factory()
            ->state(['phone' => '905555555555'])
            ->state(['address' => 'Admin Address'])
            ->make();


        $this->browse(function (Browser $browser) use ($user){
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Hesap')
                ->storeConsoleLog('account.index')
                ->screenshot('account/admin/index')
                ->waitForLocation('/account')
                ->click('#account-edit-btn')
                ->waitForLocation('/account/settings')
                ->assertSee('Hesap Ayarlarım')
                ->screenshot('account/admin/setting')
                ->pause(1000);

            $browser->type('input[name="full_name"]', $user->full_name)
                ->select('select[name="gender"]', $user->gender)
                ->type('input[name="address"]', $user->address)
                ->type('input[name="birth_date"]', now()->format('d-m-Y'))
                ->screenshot('account/admin/setting.membership')
                ->pressAndWaitFor('Kaydet',3);

            $browser->click('#contact-tab')
                ->type('input[name="phone"]', $user->phone)
                ->pressAndWaitFor('Kaydet',3)
                ->screenshot('account/admin/setting.contact');

            $browser->click('#password-tab')
                ->type('input[name="current_password"]', 'password')
                ->type('input[name="password"]', '12345678')
                ->type('input[name="password_confirmation"]', '12345678')
                ->pressAndWaitFor('Kaydet',3)
                ->screenshot('account/admin/setting.password');

            $browser->clickLink('Hesap')
                ->waitForLocation('/account')
                ->click('#logout-btn')
                ->waitForLocation('/auth/login', 10)
                ->screenshot('account/admin/logout');

            $browser->type('#email', 'admin@imtihan.tech')
                ->type('#password', '12345678')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/')
                ->screenshot('account/admin/login');

            $browser->clickLink('Hesap')
                ->waitForLocation('/account')
                ->click('#account-edit-btn')
                ->waitForLocation('/account/settings')
                ->assertSee('Hesap Ayarlarım')
                ->screenshot('account/admin/setting')
                ->pause(1500);

            $browser->screenshot('account/admin/setting.membership.uncheck')
                ->assertInputValue('input[name="full_name"]', $user->full_name)
                ->assertSelected('select[name="gender"]', Gender::Male->value)
                ->assertInputValue('input[name="address"]', $user->address)
                ->screenshot('account/admin/setting.membership.check');

            $browser->click('#contact-tab')
                ->assertInputValue('input[name="phone"]', $user->phone)
                ->screenshot('account/admin/setting.contact.check');
        });
    }
}
