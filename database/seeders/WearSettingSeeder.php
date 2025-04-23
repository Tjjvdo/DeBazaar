<?php

namespace Database\Seeders;

use App\Models\WearSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WearSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WearSetting::create(
            [
                'advertisement_id' => 2,
                'investment_amount' => 1000,
                'days_durable' => 200,
            ]
        );
    }
}
