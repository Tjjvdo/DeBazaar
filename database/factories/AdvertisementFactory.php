<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'information' => $this->faker->paragraph(),
            'advertiser_id' => User::factory(),
            'is_rentable' => false,
            'created_at' => now(),
            'inactive_at' => null,
        ];
    }

    public function rentable(): static
    {
        return $this->state(fn () => [
            'is_rentable' => true,
        ]);
    }

}