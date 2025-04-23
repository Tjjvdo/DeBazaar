<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LandingPage>
 */
class LandingPageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'slug' => Str::slug(fake()->unique()->company()),
            'component_order' => ['information', 'image', 'advertisements'],
            'info_content' => fake()->paragraph(),
            'image_path' => null,
            'color' => '#ff6600',
        ];
    }
}
