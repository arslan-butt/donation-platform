<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()?->can('make donations') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'donation_id' => 'required|exists:donations,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'gateway_transaction_id' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:1000000',
            'fee_amount' => 'nullable|numeric|min:0|max:1000000',
            'raw_response' => 'nullable|json',
            'status' => 'required|in:pending,paid,refunded,failed',
            'paid_at' => 'nullable|date',
        ];
    }
}
