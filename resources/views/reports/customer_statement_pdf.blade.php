<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customer Statement - {{ $customer->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; line-height: 1.5; }
        h1, h2, h3 { margin:0; padding:0; }
        
        .header { text-align:center; margin-bottom:25px; }
        .header h2 { font-size: 20px; font-weight:bold; margin-bottom: 5px; }
        .header p { font-size: 13px; color:#666; }

        .customer-info { margin-bottom:20px; padding:10px; border:1px solid #ccc; border-radius:6px; background:#fafafa; }
        .customer-info p { margin:4px 0; font-size: 12px; }

        table { width:100%; border-collapse: collapse; margin-bottom:20px; }
        table th, table td { border:1px solid #ccc; padding:6px 8px; text-align:left; font-size: 12px; }
        table th { background-color:#f5f5f5; font-weight:bold; }

        .totals { text-align:right; font-weight:bold; margin-top:8px; font-size: 12px; }
        .balance { font-size:14px; font-weight:bold; margin-top:10px; }
        .balance.positive { color:#28a745; }
        .balance.negative { color:#dc3545; }

        .no-transaction { 
            text-align:center; 
            font-style:italic; 
            color:#dc3545; 
            font-weight:bold; 
            margin: 20px 0; 
            font-size: 13px;
        }

        .footer {
            text-align:center; 
            font-size: 11px; 
            color:#777; 
            margin-top:40px; 
            border-top:1px solid #ddd; 
            padding-top:8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>Customer Statement</h2>
        <p>{{ $customer->name }}</p>
        <p style="font-size:12px; color:#555;">
            Period: {{ $fromDate }} to {{ $toDate }}
        </p>
    </div>


    <!-- Customer Info -->
    <div class="customer-info">
        <p><strong>Phone:</strong> {{ $customer->phone }}</p>
        <p><strong>Address:</strong> {{ $customer->address }}</p>
        <p><strong>Opening Balance:</strong> {{ number_format($customer->opening_balance ?? 0, 2) }}</p>
         <p class="balance {{ $balance >=0 ? 'positive' : 'negative' }}"> <strong> Current Balance: </strong> {{ number_format($balance,2) }} </p>
    </div>

    @if(isset($noRecords) && $noRecords)
        <p class="no-transaction">⚠ No transactions found for the selected period.</p>
    @elseif($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width:20%">Date</th>
                    <th style="width:15%">Type</th>
                    <th style="width:20%">Amount</th>
                    <th style="width:45%">Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $txn)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($txn->date)->format('d M, Y') }}</td>
                        <td>{{ ucfirst($txn->type) }}</td>
                        <td>{{ number_format($txn->amount, 2) }}</td>
                        <td>{{ $txn->notes }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Totals -->
    <p class="totals">Total Credits: {{ number_format($totalCredits,2) }}</p>
    <p class="totals">Total Debits: {{ number_format($totalDebits,2) }}</p>
   

    <!-- Footer -->
    <div class="footer">
        Generated on {{ now()->format('d M, Y h:i A') }}
    </div>
</body>
</html>
