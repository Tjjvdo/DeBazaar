<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('advertisement')->insert([
            [
                'title' => 'Antieke houten tafel',
                'price' => 150.00,
                'information' => 'Prachtige, goed onderhouden antieke tafel.',
                'advertiser_id' => 3,
                'is_rentable' => 0,
                'created_at' => now()->subWeek(),
                'inactive_at' => now(),
            ],
            [
                'title' => 'Fiets - Damesmodel',
                'price' => 75.00,
                'information' => 'Gebruikte damesfiets, werkt prima.',
                'advertiser_id' => 3,
                'is_rentable' => 1,
                'created_at' => now(),
                'inactive_at' => '2025-06-05 21:02:56',
            ],
            [
                'title' => 'Boekenkast - Eikenhout',
                'price' => 90.00,
                'information' => 'Stevige boekenkast van eikenhout, vier planken.',
                'advertiser_id' => 3,
                'is_rentable' => 0,
                'created_at' => now(),
                'inactive_at' => '2025-04-13 21:02:56',
            ],
            [
                'title' => 'Zomerjurk - Maat S',
                'price' => 25.50,
                'information' => 'Leuke, luchtige zomerjurk in maat S.',
                'advertiser_id' => 3,
                'is_rentable' => 1,
                'created_at' => now(),
                'inactive_at' => '2025-06-05 21:02:56',
            ],
            [
                'title' => 'Set van 4 koffiekopjes',
                'price' => 12.00,
                'information' => 'Nieuwe set van vier keramische koffiekopjes.',
                'advertiser_id' => 3,
                'is_rentable' => 0,
                'created_at' => now(),
                'inactive_at' => '2025-04-13 21:02:56',
            ],
        ]);
    }
}
