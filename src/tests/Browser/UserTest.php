<?php

namespace Runsite\CMF\Tests\Browser;

use Runsite\CMF\Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Artisan;

class UserTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function test_is_redirected_to_auth()
    {
        Artisan::call('runsite:setup');
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit(route('admin.boot'))
                    ->assertRouteIs('login')
                    ->assertSee(config('app.name'));
        });
    }

    public function test_user_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'))
                    ->type('email', 'developer@domain.com')
                    ->type('password', 'secret')
                    ->press('Sign In')
                    ->assertRouteIs('admin.boot');
        });
    }

    public function test_user_settings()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('admin.account.settings.edit'))
                    ->type('name', 'Tester')
                    ->press('Update settings')
                    ->assertRouteIs('admin.account.settings.edit');
        });
    }

    public function test_user_logout()
    {
        $this->browse(function (Browser $browser) {
            $browser->clickLink('Logout')
                    ->assertRouteIs('login');
        });
    }
}
