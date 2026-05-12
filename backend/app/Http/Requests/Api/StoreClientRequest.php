<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'              => 'required|string|min:3|max:200',
            'email'            => 'required|email|max:200',
            'telephone'        => 'required|string|min:8|max:20',
            'statut_paiement'  => 'required|in:payé,en_attente,en_retard',
            'date_maintenance' => 'required|date',
            'licences_count'   => 'required|integer|min:0|max:500',
        ];
    }
}
