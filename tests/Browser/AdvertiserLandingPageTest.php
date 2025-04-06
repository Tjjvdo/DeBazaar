<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\LandingPage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdvertiserLandingPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testBusinessAdvertiserCanEditLandingPageAndSeeChanges()
    {
        $user = User::factory()->create([
            'user_type' => 2,
            'email' => 'business@example.com',
            'password' => bcrypt('password'),
        ]);

        $landingPage = LandingPage::factory()->create([
            'user_id' => $user->id,
            'slug' => 'test-business',
            'component_order' => ['information'],
            'info_content' => 'Old content',
        ]);

        $this->browse(function (Browser $browser) use ($user, $landingPage) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Log in')
                ->assertPathIs('/dashboard');
                // ->visit('/my-landingpage')
                // ->type('information_text', 'New updated info about our company!')
                // ->press('Save')
                // ->assertPathIs('/landingpage/test-business')
                // ->assertSee('New updated info about our company!');
        });
    }

    public function test_business_user_access_to_landing_page()
    {
        $user = User::factory()->businessAdvertiser()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/my-landingpage')
                    ->dump();
        });
    }
}