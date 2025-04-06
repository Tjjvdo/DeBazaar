<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterPageBusinessAdvertiserTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserCanRegisterAsBusinessAdvertiser()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Test business')
                    ->type('email', 'test@example.com')
                    ->type('phone_number', '123456789')
                    ->radio('user_type', '2')
                    ->type('password', 'test1234')
                    ->type('password_confirmation', 'test1234')
                    ->press('button[type="submit"]')
                    ->assertPathIs('/dashboard');
        });
    }

    public function testUserCantRegisterAsBusinessAdvertiserWithoutPassConfirm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Test business')
                    ->type('email', 'test@example.com')
                    ->type('phone_number', '123456789')
                    ->radio('user_type', '2')
                    ->type('password', 'test1234')
                    ->press('button[type="submit"]')
                    ->assertPathIs('/register');
        });
    }
}