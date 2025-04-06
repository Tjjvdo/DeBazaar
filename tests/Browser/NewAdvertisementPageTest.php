<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Advertisement;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NewAdvertisementPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdvertiserCantCreateNewRentAdvertisement()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/newAdvertisement')
                ->type('title', 'test-business') //ad 1
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->check('input[name="rentable"]')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //ad 2
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->check('input[name="rentable"]')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //ad 3
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->check('input[name="rentable"]')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //ad 4
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->check('input[name="rentable"]')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //fail
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->check('input[name="rentable"]')
                ->pause(1000)
                ->assertPathIs('/newAdvertisement');
        });
    }

    public function testAdvertiserCantCreateNewBuyAdvertisement()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/newAdvertisement')
                ->type('title', 'test-business') //ad 1
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //ad 2
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //ad 3
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //ad 4
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->press(__('advertisements.create_button'))
                ->pause(1000)
                ->visit('/newAdvertisement')

                ->type('title', 'test-business') //fail
                ->type('price', '20')
                ->type('information', 'this is a test')
                ->pause(1000)
                ->assertPathIs('/newAdvertisement');
        });
    }

    public function testRegularUserCantAccessNewAdvertisement()
    {
        $user = User::factory()->create([
            'user_type' => 0,
        ]);
    
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/my-landingpage')
                    ->assertSee('403')
                    ->assertSee('UNAUTHORIZED');
        });

        $user = User::factory()->create([
            'user_type' => 3,
        ]);
    
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/my-landingpage')
                    ->assertSee('403')
                    ->assertSee('UNAUTHORIZED');
        });
    }
}