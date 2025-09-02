<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->name(),
            'phone'       => $this->faker->numerify($this->faker->randomElement([6,7,8,9]) . '########'),
            'email'       => $this->faker->safeEmail(),
            'password'    => Hash::make('password'), // default password
            'is_verified' => $this->faker->boolean(70), // 70% verified
        ];
    }
}
