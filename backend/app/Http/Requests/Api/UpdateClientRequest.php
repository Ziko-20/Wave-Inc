<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'              => 'sometimes|required|string|min:3|max:200',
            'email'            => 'sometimes|required|email|max:200',
            'telephone'        => 'sometimes|required|string|min:8|max:20',
            'statut_paiement'  => 'sometimes|required|in:payé,en_attente,en_retard',
            'date_maintenance' => 'sometimes|required|date',
            'licences_count'   => 'sometimes|required|integer|min:0|max:500',
            'user_id'          => 'nullable|exists:users,id',
        ];
    }
}
