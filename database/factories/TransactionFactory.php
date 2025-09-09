<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),
            'type'        => $this->faker->randomElement(['credit', 'debit']),
            'amount'      => $this->faker->randomFloat(2, 50, 1000),
            'notes'       => $this->faker->randomElement(['given cash', 'from UPI', 'Others payment']),
            'date'        => $this->faker->dateTimeBetween('2025-01-01', '2025-8-31')->format('Y-m-d'), 
        ];
    }
}
