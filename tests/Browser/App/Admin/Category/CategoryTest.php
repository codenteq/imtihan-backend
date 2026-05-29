<?php

namespace Tests\Browser\App\Admin\Category;

use App\Enums\Role;
use App\Models\Language;
use App\Models\QuestionCategory;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\AdminFrontendDuskTestCase;

/**
 * @group admin
 */
class CategoryTest extends AdminFrontendDuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testCategory(): void
    {
        User::factory(1)
            ->state(['email' => 'admin@codenteq.com'])
            ->state(['role' => Role::Admin])
            ->create();

        $category = QuestionCategory::factory()->make();
        $language = Language::factory()->create();

        $this->browse(function (Browser $browser) use ($category, $language) {
            $browser->visit('/auth/login')
                ->waitFor('#email')
                ->type('#email', 'admin@codenteq.com')
                ->type('#password', 'password')
                ->storeConsoleLog('auth')
                ->screenshot('auth')
                ->pressAndWaitFor('Giriş yap', 10)
                ->waitForLocation('/');

            $browser->clickLink('Kategoriler')
                ->storeConsoleLog('categories.index')
                ->screenshot('categories/index')
                ->waitForLocation('/categories', 3);

            $browser->press('Oluştur')
                ->type('input[name="name"]', $category->name)
                ->type('textarea[name="description"]', $category->description)
                ->select('select[name="language_id"]', $language->id)
                ->screenshot('categories/create')
                ->press('Kaydet')
                ->pause(3000)
                ->assertSeeIn('table', $category->name)
                ->storeConsoleLog('categories.last')
                ->screenshot('categories/create.index');

            $browser->press('table > tbody > tr:first-child > td > div > button')
                ->waitFor('input[name="name"]')
                ->pause(2000)
                ->screenshot('categories/edit.input')
                ->type('input[name="name"]', 'Updated '.$category->name)
                ->press('Kaydet')
                ->pause(3000)
                ->waitForText('Updated '.$category->name, 10)
                ->assertSeeIn('table', 'Updated '.$category->name)
                ->storeConsoleLog('categories.edit')
                ->screenshot('categories/edit');

            $browser->press('table > tbody > tr:first-child > td > div > button:nth-child(2)')
                ->acceptDialog()
                ->pause(3000)
                ->assertDontSeeIn('table', 'Updated '.$category->name)
                ->storeConsoleLog('categories.delete')
                ->screenshot('categories/delete');

        });
    }
}
