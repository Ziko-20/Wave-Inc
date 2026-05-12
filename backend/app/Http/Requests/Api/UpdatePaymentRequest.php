<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'montant'        => 'sometimes|required|numeric|min:0',
            'date_payment'   => 'sometimes|required|date',
            'status_payment' => 'sometimes|required|in:payé,en_attente,en_retard',
        ];
    }
}
