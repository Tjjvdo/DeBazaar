<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdvertisementReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductReview::create(
            [
                'user_id' => 4,
                'advertisement_id' => 2,
                'review' => 'Hoge  kwaliteit fiets, trapt erg makkelijk. Goed product!',
            ]
        );
    }
}
