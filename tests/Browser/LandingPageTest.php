<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\LandingPage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LandingPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testBusinessAdvertiserCanEditLandingPageInformationAndSeeChanges()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $landingPage = LandingPage::factory()->create([
            'user_id' => $user->id,
            'component_order' => ['information'],
            'info_content' => 'Old content',
        ]);

        $this->browse(function (Browser $browser) use ($user, $landingPage) {
            $browser->loginAs($user)
                ->visit('/my-landingpage')
                ->type('custom_url', 'test-business')
                ->check('input[name="use_information"]')
                ->type('information_text', 'New updated info about our company!')
                ->press(__('landingpage.save_button'))
                ->assertPathIs('/landingpage/test-business')
                ->assertSee('New updated info about our company!');
        });
    }

    public function testBusinessAdvertiserCanEditLandingPageAdsAndSeeChanges()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $landingPage = LandingPage::factory()->create([
            'user_id' => $user->id,
            'component_order' => ['advertisements'],
        ]);

        $this->browse(function (Browser $browser) use ($user, $landingPage) {
            $browser->loginAs($user)
                ->visit('/my-landingpage')
                ->type('custom_url', 'test-business')
                ->check('input[name="use_advertisements"]')
                ->press(__('landingpage.save_button'))
                ->assertPathIs('/landingpage/test-business')
                ->assertSee('Our Ads');
        });
    }

    public function testBusinessAdvertiserCantEditLandingPageWithoutImage()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $landingPage = LandingPage::factory()->create([
            'user_id' => $user->id,
            'component_order' => ['advertisements'],
        ]);

        $this->browse(function (Browser $browser) use ($user, $landingPage) {
            $browser->loginAs($user)
                ->visit('/my-landingpage')
                ->type('custom_url', 'test-business')
                ->check('input[name="use_image"]')
                ->press(__('landingpage.save_button'))
                ->assertPathIs('/my-landingpage');
        });
    }

    public function testBusinessAdvertiserCantEditLandingPageWithoutCustomURL()
    {
        $user = User::factory()->businessAdvertiserAccepted()->create();

        $landingPage = LandingPage::factory()->create([
            'user_id' => $user->id,
            'component_order' => ['advertisements'],
        ]);

        $this->browse(function (Browser $browser) use ($user, $landingPage) {
            $browser->loginAs($user)
                ->visit('/my-landingpage')
                ->type('custom_url', '')
                ->check('input[name="use_information"]')
                ->type('information_text', 'New updated info about our company!')
                ->check('input[name="use_advertisements"]')
                ->waitFor('#component_1 option[value="information"]')
                ->assertSelected('#component_1', 'information')
                ->assertSelected('#component_2', 'advertisements')
                ->press(__('landingpage.save_button'))
                ->assertPathIs('/my-landingpage');
        });
    }

    public function test_business_user_access_to_landing_page()
    {
        app()->setLocale('en');

        $user = User::factory()->businessAdvertiserAccepted()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/my-landingpage')
                    ->assertSee('My Landingpage');
        });
    }

    public function testRegularUserCantAccessLandingPageEditor()
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
            'user_type' => 1,
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