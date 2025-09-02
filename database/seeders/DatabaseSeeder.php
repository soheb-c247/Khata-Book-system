<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 5 shopkeepers (users)
        User::factory(5)
            ->has(
                Customer::factory(10) // Each user has 10 customers
                    ->has(Transaction::factory(15)) // Each customer has 15 transactions
            )
            ->create();
    }
}
