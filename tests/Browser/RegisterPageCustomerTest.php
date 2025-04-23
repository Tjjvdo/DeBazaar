<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterPageCustomerTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserCanRegisterAsCustomer()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Test business')
                    ->type('email', 'test@example.com')
                    ->type('phone_number', '123456789')
                    ->radio('user_type', '0')
                    ->type('password', 'test1234')
                    ->type('password_confirmation', 'test1234')
                    ->press('button[type="submit"]')
                    ->assertPathIs('/dashboard');
        });
    }

    public function testUserCantRegisterAsCustomerWithoutName()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Test business')
                    ->type('email', 'test@example.com')
                    ->type('phone_number', '123456789')
                    ->radio('user_type', '0')
                    ->type('password', 'test1234')
                    ->type('password_confirmation', 'test1234')
                    ->press('button[type="submit"]')
                    ->assertPathIs('/register');
        });
    }
}