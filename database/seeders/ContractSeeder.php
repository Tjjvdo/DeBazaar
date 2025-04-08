<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contract::create(
            [
                'user_id' => 2,
                'pdf_path' => 'contracts/QOM3ZI919AGhhtDSgHuqQ9uBZI6wcMUrqVCypa1e.pdf',
                'status' => 'accepted',
                'created_at' => '2025-04-08 10:36:03',
                'updated_at' => '2025-04-08 10:37:23',
            ]
            );
    }
}
