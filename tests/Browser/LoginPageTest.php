<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserCanLogin()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->assertPathIs('/dashboard');
        });
    }

    public function testUserCantLoginWithWrongPass()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'pass') // should be password
                    ->press('button[type="submit"]')
                    ->assertPathIs('/login');
        });
    }
}