<?php

namespace Runsite\CMF\Tests\Browser;

use Runsite\CMF\Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Artisan;

class ModelTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function test_listing_models()
    {
        Artisan::call('runsite:setup');
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit(route('admin.models.index'))
                    ->assertRouteIs('login')
                    ->type('email', 'developer@domain.com')
                    ->type('password', 'secret')
                    ->press('Sign In')
                    ->assertRouteIs('admin.models.index')
                    ->assertSee('Models')
                    ->assertSee('Root')
                    ->assertSee('Section')
                    ->assertSee('Admin section');
        });
    }

    public function test_creating_new_model()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('admin.models.create'))
                    ->type('name', 'test')
                    ->type('display_name', 'Test')
                    ->type('display_name_plural', 'Tests')
                    ->press('Create')
                    ->assertRouteIs('admin.models.index')
                    ->assertSee('Test');
        });
    }

    public function test_editing_model()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('admin.models.index'))
                    ->clickLink('Test')
                    ->type('name', 'second_test')
                    ->type('display_name', 'Second test')
                    ->type('display_name_plural', 'Second tests')
                    ->press('Update')
                    ->assertInputValue('name', 'second_test');
        });
    }

    public function test_model_settings()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('admin.models.settings.edit', 4))
                    ->type('nodes_ordering', 'position desc')
                    ->type('dynamic_model', 'TestModel')
                    ->press('Update')
                    ->assertRouteIs('admin.models.settings.edit', 4)
                    ->assertInputValue('nodes_ordering', 'position desc')
                    ->assertInputValue('dynamic_model', 'TestModel');
        });
    }

    public function test_model_methods()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('admin.models.methods.edit', 4))
                    ->type('get', 'SectionsController@show')
                    ->type('post', 'SectionsController@store')
                    ->type('patch', 'SectionsController@update')
                    ->type('delete', 'SectionsController@destroy')
                    ->press('Update')
                    ->assertRouteIs('admin.models.methods.edit', 4)
                    ->assertInputValue('get', 'SectionsController@show')
                    ->assertInputValue('post', 'SectionsController@store')
                    ->assertInputValue('patch', 'SectionsController@update')
                    ->assertInputValue('delete', 'SectionsController@destroy');
        });
    }

    public function test_model_methods_validator()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('admin.models.methods.edit', 4))
                    ->type('get', 'string')
                    ->type('post', 'string')
                    ->type('patch', 'string')
                    ->type('delete', 'string')
                    ->press('Update')
                    ->assertRouteIs('admin.models.methods.edit', 4)
                    ->assertInputValue('get', 'string')
                    ->assertInputValue('post', 'string')
                    ->assertInputValue('patch', 'string')
                    ->assertInputValue('delete', 'string')
                    ->assertSee('Controller@method');
        });
    }

}
