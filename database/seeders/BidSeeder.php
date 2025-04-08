<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bid')->insert([
            [
                'advertisement_id' => 1,
                'bidder_id' => 4,
                'bid_amount' => 150.00,
            ],
            [
                'advertisement_id' => 3,
                'bidder_id' => null,
                'bid_amount' => 90.00,
            ],
            [
                'advertisement_id' => 5,
                'bidder_id' => null,
                'bid_amount' => 12.00,
            ],
        ]);
    }
}
