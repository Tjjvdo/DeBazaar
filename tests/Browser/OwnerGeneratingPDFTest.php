<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;

class OwnerGeneratingPDFTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testOwnerCanGeneratePDF()
    {
        $user = User::factory()->owner()->create();

        $businessAccount = User::factory()->businessAdvertiserPending()->create();

        $this->browse(function (Browser $browser) use ($user, $businessAccount) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->visit('/business-contracts')
                    ->assertSee(__('contracts.generate_contract_button'));
        });
    }

    public function testOwnerCanUploadPDF()
    {
        $user = User::factory()->owner()->create();

        $businessAccount = User::factory()->businessAdvertiserPending()->create();
        
        $pdfFile = UploadedFile::fake()->create('contract.pdf', 1024, 'application/pdf');

        $this->browse(function (Browser $browser) use ($user, $businessAccount, $pdfFile) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->visit('/business-contracts')
                    ->assertSee(__('contracts.upload_contracts'))
                    ->select('user_id', $businessAccount->id)
                    ->attach('contract_pdf', $pdfFile)
                    ->press('button[type="submit"]')
                    ->assertPathIs('/business-contracts');
        });
    }

    public function testOwnerCantUploadPDFWithoutMakingChoice()
    {
        $user = User::factory()->owner()->create();

        $businessAccount = User::factory()->businessAdvertiserPending()->create();
        
        $pdfFile = UploadedFile::fake()->create('contract.pdf', 1024, 'application/pdf');

        $this->browse(function (Browser $browser) use ($user, $businessAccount, $pdfFile) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->visit('/business-contracts')
                    ->assertSee(__('contracts.upload_contracts'))
                    ->attach('contract_pdf', $pdfFile)
                    ->press('button[type="submit"]')
                    ->assertPathIs('/business-contracts');
        });
    }
}