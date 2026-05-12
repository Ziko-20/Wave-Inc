<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'montant'        => 'required|numeric|min:0',
            'date_payment'   => 'required|date',
            'status_payment' => 'required|in:payé,en_attente,en_retard',
        ];
    }
}
