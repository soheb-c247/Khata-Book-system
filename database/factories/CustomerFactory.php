<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'         => User::inRandomOrder()->first()->id ?? User::factory(),
            'name'            => $this->faker->name(),
            'phone'           => $this->faker->numerify($this->faker->randomElement([6,7,8,9]) . '#########'),
            'address'         => $this->faker->address(),
            'opening_balance' => $this->faker->randomFloat(2, 0, 5000),
        ];
    }
}
