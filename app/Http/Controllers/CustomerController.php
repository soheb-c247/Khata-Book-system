<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = auth()->user()->customers;
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        // Pass empty customer for new form
        return view('customers.form', ['customer' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:15|unique:customers,phone',
            'balance'=> 'nullable|numeric'
        ]);

        Customer::create($request->only('name', 'phone', 'balance'));

        return redirect()->route('customers.index')->with('success', 'Customer added successfully!');
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|max:15|unique:customers,phone,' . $customer->id,
            'balance'=> 'nullable|numeric'
        ]);

        $customer->update($request->only('name', 'phone', 'balance'));

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
