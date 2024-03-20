<?php

namespace Tests\Browser;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    //use RefreshDatabase;
    /**
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
/*        $user = User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();*/

        $this->browse(function (Browser $browser) {
            $browser->visit('http://admin-frontend:3000/auth/login')
                    ->waitFor('#email')
                    ->type('#email', 'admin@imtihan.tech')
                    ->type('#password', 'password')
                    ->storeConsoleLog('console.log')
                    ->pressAndWaitFor('Giriş yap')
                    /*->pause(20000)*/
                    ->screenshot('example');
        });
    }
}
