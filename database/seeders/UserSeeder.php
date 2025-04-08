<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alice Smith',
            'email' => 'alice.smith@platform.com',
            'password' => Hash::make('securepassword123'),
            'phone_number' => '0612345678',
            'user_type' => 3, // Platform Owner
        ]);
    
        // Adding a second user (Business Advertiser)
        User::create([
            'name' => 'Acme Corp Ads',
            'email' => 'ads@acmecorp.nl',
            'password' => Hash::make('businesspassword123'),
            'phone_number' => '0698765432',
            'user_type' => 2, // Business Advertiser
        ]);

        // Adding a third user (Customer Advertizer)
        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('customerApassword123'),
            'phone_number' => '1122334455',
            'user_type' => 1, // Customer Advertizer
        ]);

        // Adding a fourth user (Customer)
        User::create([
            'name' => 'Ingrid de Vries',
            'email' => 'ingrid.v@provider.net',
            'password' => Hash::make('customerRpassword123'),
            'phone_number' => '0655667788',
            'user_type' => 0, // Customer
        ]);
    }
}
