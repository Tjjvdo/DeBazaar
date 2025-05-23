<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'user_type' => 1, // Default to customer type
        ];
    }

    /**
     * State for a business account with an accepted contract.
     */
    public function businessAdvertiserAccepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 2,
        ])->has(Contract::factory()->accepted());
    }

    /**
     * State for a business account with an pending contract.
     */
    public function businessAdvertiserPending(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 2,
        ])->has(Contract::factory());
    }

    /**
     * State for an owner account
     */
    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 3,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
