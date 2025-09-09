<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\SecureIdService;
use Exception;
use Illuminate\Support\Str;
use PDF;
class CustomerController extends Controller
{
    public function index()
    {
        $customers = auth()->user()->customers->map(function ($c) {
            return [
                'id'              => SecureIdService::encrypt($c->id),  
                'name'            => $c->name,
                'phone'           => $c->phone,
                'opening_balance' => $c->opening_balance,
                'address'         => Str::limit($c->address, 50, '...'),
                'balance'         => '<span data-order="' . $c->balance . '" class="' . 
                                        ($c->balance > 0 ? 'text-green-600 font-medium' : 'text-red-600 font-medium') . 
                                        '">₹' . number_format(($c->balance), 2) . '</span>',
            ];
        });

        return view('customers.index', compact('customers'));
    }

    public function show(string $encryptedId)
    {
        $id = SecureIdService::decrypt($encryptedId);
        $customer = Customer::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $customer->encrypted_id = $encryptedId;

        $rows = $customer->transactions->map(function ($t) {
            return [
                'date' => $t->date->format('d M, Y'),
                'type' => ucfirst($t->type),
                'amount' => $t->amount,
                'notes' => $t->notes ?: 'N/A',
                'id' => SecureIdService::encrypt($t->id),
            ];
        });

        return view('customers.show', [
            'customer' => $customer,
            'rows' => $rows,
            'balance' => $customer->balance,
            'totalCredits' => $customer->total_credits,
            'totalDebits' => $customer->total_debits,
        ]);
    }

    public function create() 
    { 
        try {
            return view('customers.form', ['customer' => null]);
        } catch (\Exception $e) { 
            Log::error('Error loading customer form: '.$e->getMessage()); 
            return redirect()->route('customers.index')->with('error', 'Failed to open customer form.'); 
        } 
    }
    
    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            $customer = Customer::create($data);
            $customer->addOpeningBalance((float) $data['opening_balance']);

            Log::info("Customer created: ID {$customer->id} by user " . auth()->id());
            return redirect()->route('customers.index')->with('success', 'Customer added successfully!');

        } catch (Exception $e) {
            Log::error('Error while creating customer : '.$e->getMessage()); 
            return redirect()->back()->withInput()->with('error', 'Failed to add customer.');
        }
    }

    public function edit(string $encryptedId)
    {
        $id = SecureIdService::decrypt($encryptedId);

        $customer = Customer::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $customer->encrypted_id = $encryptedId;

        return view('customers.form', compact('customer'));
    }

    public function update(CustomerRequest $request, string $encryptedId)
    {
        try {
            $id = SecureIdService::decrypt($encryptedId);

            $customer = Customer::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $customer->update($request->validated());

            Log::info("Customer updated: ID {$customer->id} by user " . auth()->id());
            return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');

        } catch (Exception $e) {
            Log::error('Error while updating customer : '.$e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update customer.');
        }
    }

    public function destroy(string $encryptedId)
    {
        try {
            $id = SecureIdService::decrypt($encryptedId);

            $customer = Customer::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $customer->delete();

            Log::info("Customer deleted: ID {$customer->id} by user " . auth()->id());
            return redirect()->back()->with('success', 'Customer deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error while deleting customer : '.$e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete customer.');
        }
    }

    public function statement(Request $request, $encryptedId)
    {
        $customerId = SecureIdService::decrypt($encryptedId);
        $customer = Customer::findOrFail($customerId);

        // Base query
        $query = Transaction::where('customer_id', $customerId);

        // Default date range (all time)
        $fromDate = '-';
        $toDate   = '-';

        // Predefined ranges
        if ($request->predefined_range === 'current_month') {
            $query->whereMonth('date', now()->month)
                ->whereYear('date', now()->year);

            $fromDate = now()->startOfMonth()->format('d M, Y');
            $toDate   = now()->endOfMonth()->format('d M, Y');
        } elseif ($request->predefined_range === 'last_3_months') {
            $query->whereBetween('date', [now()->subMonths(3), now()]);

            $fromDate = now()->subMonths(3)->startOfDay()->format('d M, Y');
            $toDate   = now()->format('d M, Y');
        }

        // Custom range
        if ($request->predefined_range === 'custom') {
            if ($request->from_date) {
                $query->whereDate('date', '>=', $request->from_date);
                $fromDate = \Carbon\Carbon::parse($request->from_date)->format('d M, Y');
            }
            if ($request->to_date) {
                $query->whereDate('date', '<=', $request->to_date);
                $toDate = \Carbon\Carbon::parse($request->to_date)->format('d M, Y');
            }
        }

        $transactions = $query->orderBy('date', 'asc')->get();

        // Totals
        $totalCredits = $transactions->where('type','credit')->sum('amount');
        $totalDebits  = $transactions->where('type','debit')->sum('amount');
        $balance      = $totalCredits - $totalDebits;

        $fileType = $request->file_type ?? 'pdf';
        $action   = $request->action ?? 'download';

        if ($transactions->isEmpty()) {
            $pdf = PDF::loadView('reports.customer_statement_pdf', [
                'customer'      => $customer,
                'transactions'  => $transactions,
                'totalCredits'  => $totalCredits,
                'totalDebits'   => $totalDebits,
                'balance'       => $balance,
                'noRecords'     => true,
                'fromDate'      => $fromDate,
                'toDate'        => $toDate,
            ]);
            return $pdf->download("customer_statement_{$customer->name}.pdf");
            

            // if ($fileType === 'csv') {
            //     $filename = "customer_statement_{$customer->name}.csv";
            //     $headers = [
            //         "Content-type"        => "text/csv",
            //         "Content-Disposition" => "attachment; filename=$filename",
            //     ];
            //     $callback = function() use ($fromDate, $toDate) {
            //         $handle = fopen('php://output', 'w');
            //         fputcsv($handle, ["Period: $fromDate to $toDate"]);
            //         fputcsv($handle, ['No transactions found for the selected period.']);
            //         fclose($handle);
            //     };
            //     return response()->stream($callback, 200, $headers);
            // }

            // if ($fileType === 'excel') {
            //     return Excel::download(new EmptyExport($fromDate, $toDate), "customer_statement_{$customer->name}.xlsx");
            // }
        }

        $pdf = PDF::loadView('reports.customer_statement_pdf', compact(
            'customer', 'transactions', 'totalCredits', 'totalDebits', 'balance', 'fromDate', 'toDate'
        ));
        return $pdf->download("customer_statement_{$customer->name}.pdf");
        

        // if ($fileType === 'csv') {
        //     $filename = "customer_statement_{$customer->name}.csv";
        //     $headers = [
        //         "Content-type"        => "text/csv",
        //         "Content-Disposition" => "attachment; filename=$filename",
        //     ];
        //     $callback = function() use ($transactions, $fromDate, $toDate) {
        //         $handle = fopen('php://output', 'w');
        //         fputcsv($handle, ["Period: $fromDate to $toDate"]);
        //         fputcsv($handle, ['Date', 'Type', 'Amount', 'Note']);
        //         foreach ($transactions as $t) {
        //             fputcsv($handle, [
        //                 $t->date->format('Y-m-d'),
        //                 ucfirst($t->type),
        //                 $t->amount,
        //                 $t->notes
        //             ]);
        //         }
        //         fclose($handle);
        //     };
        //     return response()->stream($callback, 200, $headers);
        // }

        // if ($fileType === 'excel') {
        //     return Excel::download(new StatementExport($transactions, $fromDate, $toDate), "customer_statement_{$customer->name}.xlsx");
        // }

        return response()->json(['success' => true]);
    }


}
