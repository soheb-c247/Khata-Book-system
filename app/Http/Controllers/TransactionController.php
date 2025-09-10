<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Customer;
use App\Http\Requests\TransactionRequest;
use Illuminate\Support\Facades\Log;
use App\Services\SecureIdService;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\FileUploadHelper;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::whereHas('customer', fn($q) =>
            $q->where('user_id', auth()->id())
        )->with('customer')->latest();

        // Apply date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $transactions = $query->get();

        $transactions->each(function ($t) {
            $t->encrypted_id = SecureIdService::encrypt($t->id);
            $t->notes = $t->notes ?: 'N/A';  
            $t->amount = '<span class="' . 
            ($t->type === 'credit' ? 'text-green-600 font-medium' : 'text-red-600 font-medium') . 
            '">₹' . number_format($t->amount, 2) . '</span>';

        });

        return view('transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        $customers = auth()->user()->customers;
        $customer = null;

        if ($request->has('customer_id')) {
            $id = SecureIdService::decrypt($request->customer_id);
            $customer = Customer::where('user_id', auth()->id())->find($id);
        }

        return view('transactions.form', compact('customers', 'customer'));
    }

    public function store(TransactionRequest $request)
    {
        try {
            $data = $request->validated();
        
            if ($request->hasFile('file')) {
                $data['file_path'] = FileUploadHelper::saveFile($request->file('file'), 'transactions');
            }

            $transaction = Transaction::create($data);

            Log::info('Transaction created', [
                'user_id' => auth()->id(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction added successfully!');
        } catch (Exception $e) {
            Log::error('Transaction creation failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to add transaction.');
        }
    }

    public function edit(string $encryptedId)
    {
        $id = SecureIdService::decrypt($encryptedId);

        $transaction = Transaction::where('id', $id)
            ->whereHas('customer', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        $transaction->encrypted_id = $encryptedId;
        $customers = auth()->user()->customers;

        return view('transactions.form', compact('transaction', 'customers'));
    }

    public function update(TransactionRequest $request, string $encryptedId)
    {
        try {
            $id = SecureIdService::decrypt($encryptedId);

            $transaction = Transaction::where('id', $id)
                ->whereHas('customer', fn($q) => $q->where('user_id', auth()->id()))
                ->firstOrFail();
            $data = $request->validated();

            if ($request->hasFile('file')) {
                $data['file_path'] = FileUploadHelper::saveFile($request->file('file'), 'transactions', $transaction->file_path);
            }

            $transaction->update($data);

            Log::info('Transaction updated', [
                'user_id' => auth()->id(),
                'transaction_id' => $transaction->id,
            ]);

            return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
        } catch (Exception $e) {
            Log::error('Transaction update failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to update transaction.');
        }
    }

    public function destroy(string $encryptedId)
    {
        try {
            $id = SecureIdService::decrypt($encryptedId);

            $transaction = Transaction::where('id', $id)
                ->whereHas('customer', fn($q) => $q->where('user_id', auth()->id()))
                ->firstOrFail();

            $transaction->delete();

            Log::info('Transaction deleted', [
                'user_id' => auth()->id(),
                'transaction_id' => $id,
            ]);

            return redirect()->back()->with('success', 'Transaction deleted successfully!');
        } catch (Exception $e) {
            Log::error('Transaction deletion failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to delete transaction.');
        }
    }
}
