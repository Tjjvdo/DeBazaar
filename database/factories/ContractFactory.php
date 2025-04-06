<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contract::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pdf_path' => '',
            'status' => 'pending',
        ];
    }

    /**
     * Set the contract's status to accepted.
     */
    public function accepted(): static
    {
        return $this->state([
            'status' => 'accepted',
        ]);
    }

    /**
     * Set the contract's status to declined.
     */
    public function declined(): static
    {
        return $this->state([
            'status' => 'declined',
        ]);
    }
}
