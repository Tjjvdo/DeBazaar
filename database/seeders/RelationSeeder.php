<?php

namespace Database\Seeders;

use App\Models\AdvertisementRelated;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdvertisementRelated::create(
            [
                'advertisement_id' => 3,
                'related_advertisement_id' => 5,
            ]
        );
    }
}
