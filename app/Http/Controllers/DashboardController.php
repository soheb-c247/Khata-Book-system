<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $customers = Customer::where('user_id', $userId)->get();

        $totalCustomers = $customers->count();

        $totalBalance = $customers->sum(function ($c) {
            return (float) $c->balance;
        });
        
        $outstandingCustomers = $customers->sortBy('updated_at')->values();

        return view('dashboard', compact('totalCustomers', 'totalBalance', 'outstandingCustomers'));
    }
}
