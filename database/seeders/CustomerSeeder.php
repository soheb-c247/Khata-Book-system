<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Create specific user
        $user = User::create([
            'name'        => 'Shoaib',
            'phone'       => '8827738545',
            'email'       => 'shoaib@gmail.com',
            'password'    => Hash::make('password'),
            'is_verified' => 1,
        ]);

        // Create one fixed customer
        $customer1 = Customer::create([
            'user_id' => $user->id,
            'name'    => 'Customer 1',
            'phone'   => '9876543210',
            'address' => '123 Main Street, City, State',
            'opening_balance' => 1000.00,
        ]);

        // Add opening balance transaction for Customer 1
        Transaction::create([
            'customer_id' => $customer1->id,
            'type'        => 'credit',
            'amount'      => $customer1->opening_balance,
            'notes'       => 'Opening balance',
            'date'        => '2025-01-01',
        ]);

        // Create 8 random customers for this user
        $customers = Customer::factory()->count(8)->create([
            'user_id' => $user->id,
        ]);

        // Loop over all customers to create transactions
        foreach ($customers as $customer) {
            // Optional: add opening balance transaction
            Transaction::create([
                'customer_id' => $customer->id,
                'type'        => 'credit',
                'amount'      => $customer->opening_balance ?? 0,
                'notes'       => 'Opening balance',
                'date'        => '2025-01-01',
            ]);

            // Add 5-10 random transactions per customer
            Transaction::factory()->count(rand(5, 10))->create([
                'customer_id' => $customer->id,
            ]);
        }
    }
}
