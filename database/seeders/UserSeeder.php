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
            'name' => 'Platform Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('securepassword123'),
            'phone_number' => '1234567890',
            'user_type' => 3, // Platform Owner
        ]);

        // Adding a second user (Business Advertiser)
        User::create([
            'name' => 'Business Advertiser',
            'email' => 'Business@example.com',
            'password' => Hash::make('businesspassword123'),
            'phone_number' => '0987654321',
            'user_type' => 2, // Buisiness Advertiser
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
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('customerRpassword123'),
            'phone_number' => '6677889900',
            'user_type' => 0, // Customer
        ]);
    }
}
