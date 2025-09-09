<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\AuthorizeCustomerAccess;

Route::get('/', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

Route::middleware('auth')->group(function () {
    
    Route::resource('customers', CustomerController::class)->middleware(AuthorizeCustomerAccess::class);

    Route::resource('transactions', TransactionController::class)->parameters([
        'transactions' => 'encryptedId'
    ])->middleware(AuthorizeCustomerAccess::class);


    Route::post('/customers/{customer}/statement', [CustomerController::class, 'statement'])
        ->name('customers.statement');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
