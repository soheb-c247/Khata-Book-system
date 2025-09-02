<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Later we will fetch transactions from DB
        return view('transactions.index')->with('success', true);
    }
}
