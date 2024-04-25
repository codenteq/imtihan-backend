<?php

namespace Tests\Browser\App\Student\Account;

use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\Role;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\StudentFrontendDuskTestCase;

class AccountTest extends StudentFrontendDuskTestCase
{
    public function testStudentAccount(): void
    {
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => Role::Student])
            ->create();

        $country = Country::create([
            'name' => 'Türkiye',
            'code' => 'TR',
        ]);

        City::create([
            'name' => 'Ankara',
            'country_id' => $country->id,
        ]);

        $city = City::create([
            'name' => 'İstanbul',
            'country_id' => $country->id,
        ]);

        State::create([
            'name' => 'Üsküdar',
            'city_id' => $city->id,
        ]);

        $state = State::create([
            'name' => 'Florya',
            'city_id' => $city->id,
        ]);


        $user = User::factory()
            ->state(['phone' => '905555555555'])
            ->state(['address' => 'Student Address'])
            ->state(['country_id' => $country->id])
            ->state(['city_id' => $city->id])
            ->state(['state_id' => $state->id])
            ->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'student@imtihan.tech')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Hesap')
                ->storeConsoleLog('account.index')
                ->screenshot('account/student/index')
                ->waitForLocation('/account')
                ->click('#account-edit-btn')
                ->waitForLocation('/account/settings')
                ->assertSee('Hesap Ayarlarım')
                ->screenshot('account/student/setting')
                ->pause(1000)
                ->screenshot('account/student/setting.membership.first');

            $browser->pause(2000)
                ->type('input[name="full_name"]', $user->full_name)
                ->select('select[name="gender"]', $user->gender)
                ->type('input[name="address"]', $user->address)
                ->pause(1000)
                ->select('#country_id', $user->country_id)
                ->pause(500)
                ->select('#city_id', $user->city_id)
                ->pause(1500)
                ->select('#state_id', $user->state_id)
                ->select('select[name="education_level"]', EducationLevel::High->value)
                ->type('input[name="birth_date"]', now()->format('d-m-Y'))
                ->press('Kaydet')
                ->pause(1000)
                ->screenshot('account/student/setting.membership');

            $browser->click('#contact-tab')
                ->type('input[name="phone"]', $user->phone)
                ->pressAndWaitFor('Kaydet',3)
                ->screenshot('account/student/setting.contact');

            $browser->click('#password-tab')
                ->type('input[name="current_password"]', 'password')
                ->type('input[name="password"]', '12345678')
                ->type('input[name="password_confirmation"]', '12345678')
                ->pressAndWaitFor('Kaydet',3)
                ->screenshot('account/student/setting.password');

            $browser->clickLink('Hesap')
                ->waitForLocation('/account')
                ->click('#logout-btn')
                ->waitForLocation('/auth/login', 10)
                ->screenshot('account/student/logout');

            $browser->waitFor('#email')
                ->type('#email', 'student@imtihan.tech')
                ->type('#password', '12345678')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/')
                ->screenshot('account/student/login');

            $browser->clickLink('Hesap')
                ->waitForLocation('/account')
                ->click('#account-edit-btn')
                ->waitForLocation('/account/settings')
                ->assertSee('Hesap Ayarlarım')
                ->screenshot('account/student/setting')
                ->pause(3000);

            $browser->screenshot('account/student/setting.membership.uncheck')
                ->assertInputValue('input[name="full_name"]', $user->full_name)
                ->assertSelected('select[name="gender"]', Gender::Male->value)
                ->assertInputValue('input[name="address"]', $user->address)
                ->assertSelected('select[name="country_id"]', $user->country_id)
                /*->assertSelected('select[name="city_id"]', $user->city_id)
                ->assertSelected('select[name="state_id"]', $user->state_id)*/
                ->assertSelected('select[name="education_level"]', EducationLevel::High->value)
                ->screenshot('account/student/setting.membership.check');

            $browser->click('#contact-tab')
                ->assertInputValue('input[name="phone"]', $user->phone)
                ->screenshot('account/student/setting.contact.check');
        });
    }
}
