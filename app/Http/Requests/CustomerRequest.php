<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Services\SecureIdService;
use Illuminate\Support\Facades\Log;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $customerId = null;

        if ($this->route('id')) {
            try {
                $encryptedId = $this->route('id'); 
                $customerId = SecureIdService::decrypt($encryptedId);

            } catch (\Exception $e) {
                Log::error('CustomerRequest: Failed to decrypt customer ID: ' . $e->getMessage());
                $customerId = null;
            }
        }
        $rules = [
            'name'    => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s]+$/',
            ],
            'phone'   => [
                'required',
                'numeric',
                'digits:10',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('customers', 'phone')
                    ->ignore($customerId, 'id') 
                    ->where(fn ($query) => $query
                        ->where('user_id', auth()->id())
                        ->whereNull('deleted_at')
                    ),
            ],
            'address' => [
                'required',
                'string',
                'max:200',
            ],
        ];

        if (!$customerId) {
            $rules['opening_balance'] = 'nullable|numeric|min:0';
        }

        return $rules;
    }



    public function messages(): array
    {
        return [
            'name.required'             => 'Customer name is required.',
            'name.string'               => 'Customer name must be valid.',
            'name.max'                  => 'Customer name cannot exceed 100 characters.',
            'name.regex'                => 'Customer name may only contain letters and spaces.',

            'phone.required'            => 'Phone number is required.',
            'phone.numeric'             => 'Phone number must be valid digits.',
            'phone.digits'              => 'Phone number must be exactly 10 digits.',
            'phone.unique'              => 'Customer already exists with this phone number.',
            'phone.regex'               => 'Phone number must start with 6, 7, 8, or 9.',

            'address.required'          => 'Address is required.',
            'address.string'            => 'Address must be valid text.',
            'address.max'               => 'Address cannot exceed 200 characters.',

            'opening_balance.numeric'   => 'Opening balance must be a number.',
            'opening_balance.min'       => 'Opening balance cannot be negative.',
        ];
    }
}
