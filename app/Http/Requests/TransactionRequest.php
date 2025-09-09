<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'type'        => 'required|in:credit,debit',
            'amount'      => 'required|numeric|min:1',
            'date'        => 'required|date|before_or_equal:today',
            'notes'       => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists'   => 'Invalid customer selected.',
            'type.required'        => 'Transaction type is required.',
            'amount.required'      => 'Please enter an amount.',
            'amount.numeric'       => 'Amount must be a valid number.',
            'amount.min'           => 'Amount must be greater than zero.',
            'date.required'        => 'Transaction date is required.',
            'date.before_or_equal' => 'Transaction date cannot be in the future.',
        ];
    }
}
