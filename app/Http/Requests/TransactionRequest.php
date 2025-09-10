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
            'date'        => 'required|date|after_or_equal:2020-01-01|before_or_equal:2030-12-31',
            'notes'       => 'nullable|string|max:500',
            'file'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
            'date.date'            => 'Transaction date must be a valid date.',
            'date.after_or_equal'  => 'Transaction date cannot be earlier than Jan 1, 2020.',
            'date.before_or_equal' => 'Transaction date cannot be later than Dec 31, 2030.',            'file.file'   => 'The uploaded file must be valid.',
            'file.mimes'           => 'Only JPG, JPEG, PNG, or PDF files are allowed.',
            'file.max'             => 'The file size must not exceed 2 MB.',
        ];
    }
}
