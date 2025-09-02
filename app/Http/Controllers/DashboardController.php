<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total customers
        $totalCustomers = Customer::count();

        // Total balance across all customers (calculated via accessor)
        $totalBalance = Customer::all()->sum(fn($c) => $c->balance);

        // Customers with outstanding balances
        $outstandingCustomers = Customer::orderBy('updated_at', 'asc')->get();

        return view('dashboard', compact('totalCustomers', 'totalBalance', 'outstandingCustomers'));
    }
}
