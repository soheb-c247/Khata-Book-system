<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecureIdService;

class AuthorizeCustomerAccess
{
    public function handle(Request $request, Closure $next)
    {
        $param = $request->route('customer') ?? $request->route('encryptedId');

        // Skip if route has no customer/transaction
        if (!$param) {
            return $next($request);
        }

        try {
            $id = SecureIdService::decrypt($param);
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Invalid resource ID.');
        }

        $user = $request->user();

        // Determine type: Customer or Transaction
        $routeName = $request->route()->getName();

        if (str_contains($routeName, 'customers')) {
            // Customer routes
            $exists = $user->customers()->where('id', $id)->exists();
        } elseif (str_contains($routeName, 'transactions')) {
            // Transaction routes
            $exists = $user->customers()
                ->whereHas('transactions', function ($q) use ($id) {
                    $q->where('id', $id);
                })
                ->exists();
        } else {
            $exists = true;
        }

        if (!$exists) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}
